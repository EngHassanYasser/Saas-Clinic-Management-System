  {{-- DOCTOR ROW --}}
  <div class="flex items-center justify-between p-4 cursor-pointer select-none" @click="open = !open">

      <div class="flex items-center gap-3">
          {{-- Avatar --}}
          <div
              class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold text-sm flex-shrink-0">
              {{ mb_substr($doctor['name'], 0, 1) }}
          </div>
          <div>
              <p class="text-sm font-medium text-gray-800">{{$doctor['name'] }}</p>
              <p class="text-xs text-gray-400">{{ $doctor['speciality_name'] }} —
                  {{ $doctor['schedules_count'] }} جدول</p>
          </div>
      </div>

      <div class="flex items-center gap-2">
          <button type="button" @click.stop="openAdd();showModel = true"
              class="text-xs bg-teal-50 hover:bg-teal-100 text-teal-700 px-3 py-1.5 rounded-lg transition flex items-center gap-1">
              <i class="fa fa-plus text-[10px]"></i> إضافة موعد
          </button>
          <i class="fa fa-chevron-down text-gray-400 text-xs transition-transform duration-200"
              :class="open ? 'rotate-180' : ''"></i>
      </div>
  </div>
