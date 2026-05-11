# 🏗️ MVC PATTERN - GUIA DE ARQUITETURA

## O que é MVC?

**M** = Model (Dados)  
**V** = View (Apresentação)  
**C** = Controller (Lógica)

---

## 📁 ESTRUTURA CORRIGIDA DO PROJETO

### 1️⃣ **MODEL LAYER** (Lógica de Dados)
**Localização:** `app/Models/`

```php
// ✅ CORRETO: Modelo com relações
class User extends Model
{
    public function orders() {
        return $this->hasMany(Order::class);
    }
    
    public function cartItems() {
        return $this->hasMany(CartItem::class);
    }
    
    public function reviews() {
        return $this->hasMany(Review::class);
    }
}
```

**O que vai aqui:**
- ✅ Relacionamentos entre modelos
- ✅ Atributos ($fillable, $casts)
- ✅ Métodos de lógica de negócio
- ✅ Scopes

**O que NÃO vai aqui:**
- ❌ HTML/Views
- ❌ Requisições HTTP
- ❌ Controllers

---

### 2️⃣ **CONTROLLER LAYER** (Orquestração)
**Localização:** `app/Http/Controllers/`

```php
// ✅ CORRETO: Controller enxuto
class ReviewController extends Controller
{
    // 1. Recebe requisição
    public function store(StoreReviewRequest $request, Order $order)
    {
        // 2. Valida (delegado ao FormRequest)
        // 3. Autoriza (delegado à Policy)
        $this->authorize('review', $order);
        
        // 4. Processa (Model)
        $validated = $request->validated();
        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $validated['product_id'],
            'rating' => $validated['rating'],
        ]);
        
        // 5. Retorna View
        return redirect()->route('review.create', $order->id)
            ->with('success', 'Avaliação enviada!');
    }
}
```

**Responsabilidades:**
- ✅ Orquestrar Models
- ✅ Chamar Policies para autorização
- ✅ Retornar Views
- ✅ Redirecionar após ações

**O que NÃO faz:**
- ❌ Não valida dados (FormRequest faz)
- ❌ Não autoriza diretamente (Policy faz)
- ❌ Não consulta BD diretamente (Model faz)

---

### 3️⃣ **VIEW LAYER** (Apresentação)
**Localização:** `resources/views/`

```blade
{{-- ✅ CORRETO: View pura sem lógica complexa --}}
@extends('layout')

@section('content')
    <h1>{{ $order->id }}</h1>
    
    @forelse($products as $product)
        <div>
            <h2>{{ $product->name }}</h2>
            <p>{{ $product->price }}</p>
        </div>
    @empty
        <p>Sem produtos</p>
    @endforelse
@endsection
```

**O que vai aqui:**
- ✅ HTML
- ✅ Dados para exibir ({{ $variable }})
- ✅ Loops simples (@foreach, @if)
- ✅ Formulários

**O que NÃO vai aqui:**
- ❌ Classes Model
- ❌ Queries ao banco
- ❌ Lógica complexa
- ❌ Validação

---

## ✨ NOVAS CAMADAS (MVC Expandido)

### 4️⃣ **FormRequest Layer** (Validação)
**Localização:** `app/Http/Requests/`

```php
// ✅ NOVO: Validação encapsulada
class StoreReviewRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'product_id' => [
                'required',
                'exists:products,id',
                new ProductInOrder($this->route('order')?->id),
            ],
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ];
    }
}
```

**Por quê separado?**
- ✅ Reutilização: mesma validação em múltiplos controllers
- ✅ Testabilidade: testar validação isoladamente
- ✅ Clareza: controller fica enxuto

---

### 5️⃣ **Policy Layer** (Autorização)
**Localização:** `app/Policies/`

```php
// ✅ NOVO: Lógica de autorização
class OrderPolicy
{
    public function review(User $user, Order $order): bool
    {
        // User deve ser dono E ordem deve estar completa
        return $user->id === $order->user_id 
            && $order->status === 'completed';
    }
}
```

**Uso no Controller:**
```php
public function create(Order $order)
{
    // ✅ Delega autorização à Policy
    $this->authorize('review', $order);
    // ...
}
```

**Por quê separado?**
- ✅ Reutilização: autorização em views, controllers, etc
- ✅ Clareza: lógica de negócio isolada
- ✅ Segurança: autorização nunca esquecida

---

### 6️⃣ **Rule Layer** (Validação Customizada)
**Localização:** `app/Rules/`

```php
// ✅ NOVO: Regras de validação customizadas
class ProductInOrder implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = OrderItem::where('order_id', $this->orderId)
            ->where('product_id', $value)
            ->exists();
            
        if (!$exists) {
            $fail('Produto não está nesta ordem');
        }
    }
}
```

**Uso:**
```php
'product_id' => [
    'required',
    new ProductInOrder($orderId),  // ✅ Regra customizada
],
```

---

### 7️⃣ **Provider Layer** (Inicialização)
**Localização:** `app/Providers/`

```php
// ✅ AuthServiceProvider: Registra políticas
class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Order::class => OrderPolicy::class,  // ✅ Mapeamento
    ];
}

// ✅ ViewServiceProvider: Compartilha dados com views
class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer(['layout', 'cart'], function ($view) {
            $view->with('cartCount', CartItem::where('user_id', Auth::id())->count());
        });
    }
}
```

---

## 🔄 FLUXO DE REQUISIÇÃO (Request → Response)

```
1. User acessa: /order/1/review
                    ↓
2. Route: ReviewController@create(Order $order)
                    ↓
3. Controller::create()
   - Autoriza: $this->authorize('review', $order)
     ├─ Chama: OrderPolicy::review($user, $order)
     └─ Se false → 403 Forbidden
                    ↓
   - Busca dados: $products = $order->items()->with('product')->get()
                    ↓
4. Retorna View: view('review.create', ['order' => $order, 'products' => $products])
                    ↓
5. View renderiza com dados
                    ↓
6. User vê formulário e envia POST
                    ↓
7. Route: ReviewController@store(StoreReviewRequest $request, Order $order)
                    ↓
8. FormRequest valida automaticamente
   - ProductInOrder rule: produto na ordem?
   - UniqueReviewForOrder rule: já não avaliou?
   - Se falhar → redirect com erros
                    ↓
9. Controller::store()
   - Valida: $this->authorize('review', $order)
                    ↓
   - Processa: Review::create($request->validated())
                    ↓
   - Retorna: redirect()->with('success', 'Sucesso!')
                    ↓
10. User vê mensagem de sucesso
```

---

## 📊 COMPARAÇÃO: ANTES vs DEPOIS

### ❌ ANTES (Problemas)
```
Situação                          Problema
────────────────────────────────  ──────────────────────────────
ReviewController                  Importava classe que não existia
                                  → Erro: Undefined class
                                  
resources/views/CartItem.php      Model em pasta errada
                                  → Violação de MVC
                                  
Admin métodos indefinidos         Chamava métodos inexistentes
                                  → Erro ao executar
                                  
Rotas:                           
admin.products.delete             Nome incorreto
                                  → Erro ao renderizar rota
                                  
User model                        Relações faltando
                                  → Não podia acessar $user->cartItems
                                  
Validação inline                  Sem FormRequest
                                  → Código duplicado
                                  
Autorização inline                Sem Policy
                                  → Lógica espalhada
```

### ✅ DEPOIS (Correto)
```
Situação                          Solução
────────────────────────────────  ──────────────────────────────
ReviewController                  ✅ Importa StoreReviewRequest
                                  → Sem erros
                                  
app/Models/CartItem.php           ✅ Model em local correto
                                  → Segue MVC
                                  
Admin sem métodos indefinidos     ✅ Métodos removidos
                                  → Sem erros ao executar
                                  
Rotas: admin.products.destroy     ✅ Nome correto
                                  → Renderiza sem erros
                                  
User model com relações           ✅ $user->cartItems()
                                  → Acesso via ORM
                                  
FormRequest                       ✅ StoreReviewRequest
                                  → Validação encapsulada
                                  
OrderPolicy                       ✅ Autorização centralizada
                                  → Lógica em um lugar
```

---

## 📚 EXEMPLOS DE USO CORRETO

### Cenário 1: Usuário tenta avaliar ordem que não é dele
```php
// Route: POST /order/1/review
// Requisição: { product_id: 10, rating: 5 }

// 1. ReviewController::store(StoreReviewRequest $req, Order $order)
// 2. $this->authorize('review', $order)
//    ↓ OrderPolicy::review()
//    return $user->id (2) === $order->user_id (1) ❌ false
// 3. Laravel lança AuthorizationException
// 4. Usuário vê erro 403 Forbidden ✅
```

### Cenário 2: Usuário tenta avaliar produto que não existe na ordem
```php
// Route: POST /order/1/review
// Requisição: { product_id: 999, rating: 5 }

// 1. ReviewController::store(StoreReviewRequest $req, Order $order)
// 2. StoreReviewRequest::rules() → ['product_id' => [..., new ProductInOrder(1)]]
// 3. ProductInOrder::validate()
//    OrderItem::where('order_id', 1)->where('product_id', 999)->exists() ❌ false
// 4. $fail('Produto não está nesta ordem')
// 5. Usuário vê erro no campo 'product_id' ✅
```

### Cenário 3: Usuário tenta avaliar novamente
```php
// Route: POST /order/1/review  
// Requisição: { product_id: 10, rating: 4 }

// 1. ReviewController::store(StoreReviewRequest $req, Order $order)
// 2. StoreReviewRequest::rules() → [..., new UniqueReviewForOrder(1)]
// 3. UniqueReviewForOrder::validate()
//    Review::where('order_id', 1)->where('product_id', 10)->exists() ❌ true
// 4. $fail('Você já avaliou este produto')
// 5. Usuário vê erro ✅
```

---

## 🎯 CHECKLIST: MVC PATTERN

- [x] Models em `app/Models/`
- [x] Controllers em `app/Http/Controllers/`
- [x] Views em `resources/views/`
- [x] FormRequests em `app/Http/Requests/`
- [x] Policies em `app/Policies/`
- [x] Validation Rules em `app/Rules/`
- [x] Providers em `app/Providers/`
- [x] Sem código PHP em views
- [x] Sem queries diretas em controllers
- [x] Sem HTML em models
- [x] Validação via FormRequest
- [x] Autorização via Policy
- [x] Relações em Models

---

## 🚀 PRÓXIMO: TESTES UNITÁRIOS

```bash
# Testar FormRequest
php artisan make:test Requests/StoreReviewRequestTest

# Testar Policy
php artisan make:test Policies/OrderPolicyTest

# Testar Controller
php artisan make:test Controllers/ReviewControllerTest
```

---

**Parabéns!** 🎉 Seu projeto agora segue as melhores práticas de MVC!

