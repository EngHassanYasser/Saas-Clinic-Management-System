{{-- Nav --}}
<nav class="flex-1 p-4 space-y-1">

    @php
        $role = auth()->user()->type;
        $items = config("sidebar.$role", []);
    @endphp

    @foreach ($items as $item)
        @php
            $isActive = request()->routeIs($item['route']);
        @endphp

        <a href="{{ route($item['route']) }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition
           {{ $isActive ? 'bg-white text-gray-900' : 'text-white hover:bg-white/10' }}">

            <i class="{{ $item['icon'] }} w-4 text-center"></i>
            {{ $item['label'] }}
        </a>
    @endforeach

</nav>
