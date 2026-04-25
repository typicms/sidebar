<li class="sidebar-item sidebar-item-{{ $item->id }} {{ $item->state }}">
    <a class="sidebar-item-link" href="{{ $item->route }}">
        <span class="sidebar-item-link-icon icon">
            {!! $item->icon ?? '<i class="icon-grid-2x2"></i>' !!}
        </span>
        <span class="sidebar-item-link-label">{{ $item->name }}</span>
    </a>
</li>
