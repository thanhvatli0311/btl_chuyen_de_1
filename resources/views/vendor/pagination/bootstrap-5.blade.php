@if ($paginator->hasPages())
    <nav class="d-flex justify-content-center" role="navigation" aria-label="Pagination Navigation">
        <ul class="pagination pagination-sm mb-0" style="gap: 3px;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" style="padding: 0.25rem 0.5rem; border-radius: 3px; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; opacity: 0.5;">
                        <i class="fas fa-chevron-left" style="font-size: 0.875rem;"></i>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" style="padding: 0.25rem 0.5rem; border-radius: 3px; border: 1px solid #dee2e6; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; color: #0d6efd; transition: all 0.2s ease;">
                        <i class="fas fa-chevron-left" style="font-size: 0.875rem;"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link" style="padding: 0.25rem 0.375rem; border: none; color: #6c757d; font-size: 0.75rem;">...</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link" style="padding: 0.25rem 0.5rem; border-radius: 3px; background-color: #0d6efd; border-color: #0d6efd; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; font-size: 0.875rem;">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}" style="padding: 0.25rem 0.5rem; border-radius: 3px; border: 1px solid #dee2e6; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; color: #0d6efd; text-decoration: none; font-size: 0.875rem; transition: all 0.2s ease;">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" style="padding: 0.25rem 0.5rem; border-radius: 3px; border: 1px solid #dee2e6; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; color: #0d6efd; transition: all 0.2s ease;">
                        <i class="fas fa-chevron-right" style="font-size: 0.875rem;"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" style="padding: 0.25rem 0.5rem; border-radius: 3px; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; opacity: 0.5;">
                        <i class="fas fa-chevron-right" style="font-size: 0.875rem;"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
