      {{-- Nav --}}
      <nav class="flex-1 p-4 space-y-1">

          @php
              $role = auth()->user()->type; // أو spatie permission لو عندك
              $items = config("sidebar.$role", []);
          @endphp
          @foreach ($items as $item)
              <a href="{{ route($item['route']) }}"
                  class="flex items-center gap-3 px-4 py-3 rounded-xl  hover:bg-white/10 text-white font-semibold text-sm">
                  <i class="{{ $item['icon'] }} w-4 text-center"></i>
                  {{ $item['label'] }}
              </a>
          @endforeach
      </nav>
