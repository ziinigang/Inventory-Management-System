@props(['href', 'active' => false])

<a href="{{ $href }}"
   class="nav-link {{ $active ? 'active' : '' }}">
    {{ $slot }}
</a>