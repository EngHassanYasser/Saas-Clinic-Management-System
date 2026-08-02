  <div class="relative" x-data="{ open: false }">
    <input type="hidden" x-model="currentClinic.close_time" name="close_time"/>

      <label class="block text-center text-sm font-semibold text-gray-700 mb-1">
          <i class="fa-solid fa-door-closed text-teal-600"></i>
          نهاية العمل
      </label>

      <p class="text-xs text-gray-400 text-center mb-2">
          وقت انتهاء استقبال المرضى
      </p>

      <button type="button" @click="open=!open"
          class="w-full flex items-center justify-center rounded-xl border border-gray-200 p-3 bg-white hover:border-teal-500">

          <span x-text="format12Hour(currentClinic.close_time)"></span>

          <i class="fa-solid fa-chevron-down text-gray-400"></i>
      </button>

      <div x-cloak x-show="open" x-transition @click.outside="open=false"
          class="absolute z-50 mt-2 w-full rounded-xl border border-gray-200 bg-white shadow-lg max-h-72 overflow-y-auto">

          <template x-for="hour in 24" :key="hour">

              <template x-for="minute in ['00','15','30','45']" :key="minute">

                  <button type="button"
                      @click="
                                        currentClinic.close_time =
                                        String(hour-1).padStart(2,'0') + ':' + minute + ':00';
                                        open=false;
                                    "
                      class="w-full px-4 py-2 text-center hover:bg-teal-50">

                      <span x-text="format12Hour(String(hour-1).padStart(2,'0') + ':' + minute + ':00')">
                      </span>

                  </button>

              </template>

          </template>

      </div>

  </div>
