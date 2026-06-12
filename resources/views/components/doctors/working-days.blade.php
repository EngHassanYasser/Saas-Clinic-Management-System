  {{-- أيام العمل --}}
  <div class="bg-white rounded-xl border border-gray-100 p-5">
      <h2 class="text-sm font-medium text-gray-700 mb-4 flex items-center gap-2">
          <i class="fa fa-calendar text-teal-500"></i> أيام العمل
      </h2>
      <div class="flex flex-wrap gap-2">
          @php
              $days = [
                  'saturday' => 'السبت',
                  'sunday' => 'الأحد',
                  'monday' => 'الاثنين',
                  'tuesday' => 'الثلاثاء',
                  'wednesday' => 'الأربعاء',
                  'thursday' => 'الخميس',
                  'friday' => 'الجمعة',
              ];
          @endphp
          @foreach ($days as $val => $label)
              <label class="cursor-pointer">
                  <input type="checkbox" name="work_days[]" value="{{ $val }}" class="hidden" x-model="workDays"
                      @change="generateSlots()">
                  <span class="inline-block px-4 py-2 rounded-lg text-sm border transition select-none"
                      :class="workDays.includes('{{ $val }}') ?
                          'bg-teal-500 text-white border-teal-500' :
                          'text-gray-600 border-gray-200'">
                      {{ $label }}
                  </span>
              </label>
          @endforeach
      </div>
      @error('work_days')
          <p class="text-xs text-red-400 mt-2">{{ $message }}</p>
      @enderror
  </div>
