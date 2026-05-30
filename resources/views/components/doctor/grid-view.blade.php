  {{-- ===================== GRID VIEW ===================== --}}
  <div id="gridContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">

  
      @foreach ($doctors as $doctor)
          <div class="doctor-card bg-white rounded-xl border border-gray-100 p-5 flex flex-col gap-3 hover:-translate-y-1 transition duration-200"
              data-name="{{ $doctor['name'] }}" data-specialty="{{ $doctor['specialty'] }}"
              data-status="{{ $doctor['active'] ? 'active' : 'inactive' }}">

              <div class="flex items-start justify-between">
                  <div
                      class="w-14 h-14 rounded-xl {{ $doctor['color'] }} flex items-center justify-center text-lg font-medium flex-shrink-0">
                      {{ $doctor['initials'] }}
                  </div>
                  <span
                      class="text-xs px-2.5 py-1 rounded-full {{ $doctor['active'] ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                      {{ $doctor['active'] ? 'متاح' : 'غير متاح' }}
                  </span>
              </div>

              <div>
                  <p class="text-sm font-medium text-gray-800">{{ $doctor['name'] }}</p>
                  <p class="text-xs text-teal-600 mt-0.5">{{ $doctor['specialty'] }}</p>
              </div>

              <div class="flex flex-col gap-1.5 text-xs text-gray-400">
                  <div class="flex items-center gap-1.5">
                      <i class="fa fa-money-bill-wave text-gray-300 w-3"></i>
                      <span>{{ $doctor['price'] }} ج.م / كشف</span>
                  </div>
                  <div class="flex items-center gap-1.5">
                      <i class="fa fa-clock text-gray-300 w-3"></i>
                      <span>{{ $doctor['duration'] }} دقيقة / موعد</span>
                  </div>
                  <div class="flex items-center gap-1.5">
                      <i class="fa fa-calendar-days text-gray-300 w-3"></i>
                      <span>{{ $doctor['days'] }}</span>
                  </div>
                  <div class="flex items-center gap-1.5">
                      <i class="fa fa-calendar-check text-gray-300 w-3"></i>
                      <span>{{ $doctor['appointments'] }} موعد هذا الشهر</span>
                  </div>
              </div>

              <div class="flex items-center gap-2 mt-auto pt-3 border-t border-gray-50">
                 <button type="button"
    class="flex-1 text-center text-xs text-teal-600 border border-teal-200 hover:bg-teal-50 py-1.5 rounded-lg transition">
    <i class="fa fa-pen ml-1"></i>
    تعديل
</button>
                  
                  <a href="#"
                      class="flex-1 text-center text-xs text-blue-600 border border-blue-200 hover:bg-blue-50 py-1.5 rounded-lg transition">
                      <i class="fa fa-eye ml-1"></i> عرض
                  </a>
                  <button onclick="confirmDelete(this)"
                      class="w-8 h-8 flex items-center justify-center text-red-400 border border-red-200 hover:bg-red-50 rounded-lg transition flex-shrink-0">
                      <i class="fa fa-trash text-xs"></i>
                  </button>
              </div>
          </div>
      @endforeach

  </div>
