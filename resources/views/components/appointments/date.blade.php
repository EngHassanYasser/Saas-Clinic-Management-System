  {{-- Date --}}
  <div x-show="currencSection === dateTimeSection" x-transition class="fade-in">
      <label class="block text-sm font-semibold text-gray-800 mb-2">
          اليوم
          <button type="button" class="text-xs text-teal-600 font-normal" @click="goToStep(doctorSection)">
              (تغيير الدكتور)
          </button>
      </label>
      <div class="flex gap-2 overflow-x-auto pb-2 -mx-1 px-1">
          <template x-for="d in availableDates" :key="d.dateStr">
              <button type="button" @click="!d.isFriday && selectDate(d)" :disabled="d.isFriday"
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

      {{-- Slots --}}
      <div x-show="" x-transition class="fade-in mt-4">
          <label class="block text-sm font-semibold text-gray-800 mb-2">الأوقات المتاحة</label>

          <div x-show="slotsLoading" class="py-8 flex flex-col items-center gap-2 text-gray-400">
              <svg class="animate-spin h-6 w-6 text-teal-600" viewBox="0 0 24 24" fill="none">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                      stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
              </svg>
              <span class="text-xs">جاري تحميل المواعيد...</span>
          </div>

          <div x-show="!slotsLoading && slots.length > 0" class="grid grid-cols-3 sm:grid-cols-4 gap-2">
              <template x-for="slot in slots" :key="slot.start">
                  <button type="button" @click="!slot.booked && selectSlot(slot)" :disabled="slot.booked"
                      class="slot-btn py-2.5 rounded-lg text-xs sm:text-sm font-medium border-2 transition-colors"
                      :class="slot.booked ?
                          'bg-gray-50 text-gray-300 border-gray-100 cursor-not-allowed line-through' :
                          (selected.slot?.start === slot.start ?
                              'border-teal-500 bg-teal-600 text-white' :
                              'border-gray-100 bg-gray-50 text-gray-700 hover:border-teal-300 hover:bg-teal-50')"
                      x-text="slot.label"></button>
              </template>
          </div>

          <p x-show="!slotsLoading && slots.length === 0" class="text-sm text-gray-400 text-center py-6">
              مفيش مواعيد متاحة في اليوم ده، جرب يوم تاني
          </p>
      </div>
  </div>
