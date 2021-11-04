<div class="alert alert-custom alert-white alert-shadow gutter-b" role="alert">
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        @if (isset($items) && is_array($items))
        @foreach ($items as $item)
            @if (is_array($item) && isset($item['url'], $item['title']))
            <li class="breadcrumb-item">
                @if ($loop->last)
                <a href="{{ url($item['url']) }}">
                @else
                <a href="{{ url($item['url']) }}" class="text-muted">
                @endif
                    @if ($loop->first)
                        <i class="fa fa-home" style="margin-right: 8px;"></i>
                    @endif

                    {{ $item['title'] }}
                </a>
            </li>
            @endif
        @endforeach
        @endif
    </ul>
</div>
