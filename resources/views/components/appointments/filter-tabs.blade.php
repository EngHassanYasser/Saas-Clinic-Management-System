
  {{-- ===== FILTER TABS ===== --}}
  <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-none flex-nowrap">
      @php
          $filters = [
              'all' => ['label' => 'الكل', 'icon' => 'fa-th-large'],
              'upcoming' => ['label' => 'القادمة', 'icon' => 'fa-clock'],
              'confirmed' => ['label' => 'مؤكدة', 'icon' => 'fa-check'],
              'pending' => ['label' => 'معلقة', 'icon' => 'fa-hourglass-half'],
              'completed' => ['label' => 'مكتملة', 'icon' => 'fa-check-double'],
              'cancelled' => ['label' => 'ملغية', 'icon' => 'fa-ban'],
          ];
          $active = request('filter', 'all');
      @endphp

      @foreach ($filters as $key => $f)
          <a href="{{ request()->fullUrlWithQuery(['filter' => $key]) }}"
              class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold whitespace-nowrap transition border
             {{ $active === $key ? 'c-tab-active border-transparent' : 'bg-white border-gray-200 text-gray-600 hover:border-teal-300 hover:text-teal-700' }}">
              <i class="fas {{ $f['icon'] }} text-xs"></i>
              {{ $f['label'] }}
          </a>
      @endforeach
  </div>
