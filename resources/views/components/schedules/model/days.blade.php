  {{-- الأيام --}}
  <div>
      <label class="block text-xs text-gray-500 mb-2">
          الأيام <span class="text-red-400">*</span>
      </label>

      <div class="flex flex-wrap gap-2">
          <template x-for="day in weekDays" :key="day.id">
              <button type="button" @click="toggleDay(day.id)"
                  :class="selectedDays.includes(day.id) ?
                      'bg-teal-500 text-white border-teal-500' :
                      'text-gray-600 border-gray-200'"
                  class="px-3 py-1.5 rounded-lg text-xs border transition">
                  <span x-text="day.name"></span>
              </button>
          </template>
      </div>

      <template x-for="day in selectedDays" :key="day">
          <input type="hidden" name="day_ids[]" :value="day">
      </template>
  </div>
