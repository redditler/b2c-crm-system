@if (is_string($item))
    <div class="header">{{ $item }}</div>
@else
    <!-- {{ $item['text'] }} -->
    <div class="{{ $item['type'] }}__item {{ $item['class'] }}" id="{{ isset($item['page']) ? $item['page'] : '' }}">
        <a href="{{ $item['href'] }}" class="{{ $item['type'] }}__link" @if (isset($item['target'])) target="{{ $item['target'] }}" @endif>
            <span class="link--title">{{ $item['text'] }}</span>
            @if (isset($item['label']))
            <div class="link__badge">
                <span class="badge--vlaue">{{ $item['label'] }}</span>
            </div>
            @elseif (isset($item['submenu']))
            <span class="dropdown--icon fa fa-chevron-left"></span>
            @endif
        </a>
        @if (isset($item['submenu']))
            <div class="dropdown__menu">
                @each('adminlte::partials.menu-item', $item['submenu'], 'item')
            </div>
        @endif
    </div>
@endif

