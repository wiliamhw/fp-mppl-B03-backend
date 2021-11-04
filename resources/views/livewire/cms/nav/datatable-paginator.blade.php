@if ($paginator->hasPages())
    <div class="d-flex flex-wrap dt-pagination-container">
        @if ($paginator->onFirstPage())
            <button wire:click.prevent="goTo(1)" type="button" class="btn btn-icon btn-sm border-0 btn-light dt-pagination-btn-disabled mr-2">
                <i class="ki ki-bold-double-arrow-back icon-xs"></i>
            </button>
            <button wire:click.prevent="goTo({{ $paginator->currentPage() - 1 }})" type="button" class="btn btn-icon btn-sm border-0 btn-light dt-pagination-btn-disabled mr-2">
                <i class="ki ki-bold-arrow-back icon-xs"></i>
            </button>
        @else
            <button wire:click.prevent="goTo(1)" type="button" class="btn btn-icon btn-sm border-0 btn-light mr-2">
                <i class="ki ki-bold-double-arrow-back icon-xs"></i>
            </button>
            <button wire:click.prevent="goTo({{ $paginator->currentPage() - 1 }})" type="button" class="btn btn-icon btn-sm border-0 btn-light mr-2">
                <i class="ki ki-bold-arrow-back icon-xs"></i>
            </button>
        @endif

        @php
            $windows = \Cms\Pagination\UrlWindow::make($paginator);
            $slider = null;

            foreach ($windows as $window) {
                if (is_array($window) && (!is_array($slider) || (count($window) > count($slider)))) {
                    $slider = $window;
                }
            }
        @endphp

        @foreach ($slider as $page => $url)
            @if ($page === $paginator->currentPage())
                <button wire:click.prevent="goTo({{ $page }})" type="button" class="btn btn-icon btn-sm border-0 btn-light btn-hover-primary active mr-2">
                    {{ $page }}
                </button>
            @else
                <button wire:click.prevent="goTo({{ $page }})" type="button" class="btn btn-icon btn-sm border-0 btn-light mr-2">
                    {{ $page }}
                </button>
            @endif
        @endforeach


        @if ($paginator->hasMorePages())
            <button wire:click.prevent="goTo({{ $paginator->currentPage() + 1 }})" type="button" class="btn btn-icon btn-sm border-0 btn-light mr-2">
                <i class="ki ki-bold-arrow-next icon-xs"></i>
            </button>
            <button wire:click.prevent="goTo({{ $paginator->lastPage() }})" type="button" class="btn btn-icon btn-sm border-0 btn-light mr-2">
                <i class="ki ki-bold-double-arrow-next icon-xs"></i>
            </button>
        @else
            <button type="button" class="btn btn-icon btn-sm border-0 btn-light dt-pagination-btn-disabled mr-2">
                <i class="ki ki-bold-arrow-next icon-xs"></i>
            </button>
            <button type="button" class="btn btn-icon btn-sm border-0 btn-light dt-pagination-btn-disabled mr-2">
                <i class="ki ki-bold-double-arrow-next icon-xs"></i>
            </button>
        @endif
    </div>
@endif
