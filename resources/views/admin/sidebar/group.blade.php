<li class="sidebar-panel sidebar-panel-{{ $group->id }}">
    @if ($group->shouldShowHeading())
        <a class="sidebar-panel-title @if (config('typicms.user.menus_' . $group->id . '_collapsed')) collapsed @endif" href="#{{ $group->id }}" data-bs-toggle="collapse">
            {{ $group->name }}
        </a>
    @endif
    <ul class="sidebar-panel-collapse collapse @if (!config('typicms.user.menus_' . $group->id . '_collapsed')) show @endif" id="{{ $group->id }}">
        @foreach ($group->getItems() as $item)
            {!! $item->render() !!}
        @endforeach
    </ul>
</li>
