  <label class="block text-sm font-semibold text-gray-800 mb-2">
      اليوم
      <button type="button" class="text-xs text-teal-600 font-normal" @click="goToStep(doctorSection)">
          (تغيير الدكتور)
      </button>
  </label>

  <div class="flex gap-2 overflow-x-auto pb-2 -mx-1 px-1">
      <template x-for="d in availableDates" :key="d.dateStr">
          <button type="button" @click="handleDateClick(d)" :disabled="d.isFriday"
              class="shrink-0 flex flex-col items-center justify-center w-16 h-20 rounded-xl border-2 transition-colors"
              :class="d.isFriday ?
                  'border-gray-100 bg-gray-50 opacity-40 cursor-not-allowed' :
                  (selected.date?.dateStr === d.dateStr ?
                      'border-teal-500 bg-teal-50 ring-2 ring-teal-200' :
                      'border-gray-100 bg-gray-50 hover:border-teal-200 hover:bg-teal-50')">
              <span class="text-[11px] text-gray-500" x-text="d.dayName"></span>
              <span class="text-lg font-bold text-gray-900" x-text="d.dayNumber"></span>
              <span class="text-[10px] text-gray-400" x-text="d.monthName"></span>
          </button>
      </template>
  </div>
