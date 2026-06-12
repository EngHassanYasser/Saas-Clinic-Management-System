  {{-- ===== ADD MODAL ===== --}}
  <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4"
      @keydown.escape.window="showAddModal = false">

      <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg" @click.outside="showAddModal = false">

          <div class="flex items-center justify-between p-5 border-b">
              <h2 class="font-semibold text-gray-800">إضافة موعد — {{ $doctor->name }}</h2>
              <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600">
                  <i class="fa fa-xmark"></i>
              </button>
          </div>

          <form action="" method="POST" x-data="{
              selectedDays: [],
              toggleDay(day) {
                  if (this.selectedDays.includes(day))
                      this.selectedDays = this.selectedDays.filter(d => d !== day);
                  else
                      this.selectedDays.push(day);
              }
          }">
              @csrf
              <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

              <div class="p-5 flex flex-col gap-4">

                  {{-- الأيام --}}
                  <div>
                      <label class="block text-xs text-gray-500 mb-2">الأيام <span class="text-red-400">*</span></label>
                      <div class="flex flex-wrap gap-2">
                          @foreach (['saturday' => 'السبت', 'sunday' => 'الأحد', 'monday' => 'الاثنين', 'tuesday' => 'الثلاثاء', 'wednesday' => 'الأربعاء', 'thursday' => 'الخميس', 'friday' => 'الجمعة'] as $val => $label)
                              <button type="button" @click="toggleDay('{{ $val }}')"
                                  :class="selectedDays.includes('{{ $val }}') ?
                                      'bg-teal-500 text-white border-teal-500' :
                                      'text-gray-600 border-gray-200'"
                                  class="px-3 py-1.5 rounded-lg text-xs border transition">
                                  {{ $label }}
                              </button>
                          @endforeach
                      </div>
                      {{-- hidden inputs للأيام --}}
                      <template x-for="day in selectedDays" :key="day">
                          <input type="hidden" name="days[]" :value="day">
                      </template>
                  </div>

                  {{-- أوقات العمل --}}
                  <div class="grid grid-cols-2 gap-3">
                      <div>
                          <label class="block text-xs text-gray-500 mb-1.5">بداية الدوام <span
                                  class="text-red-400">*</span></label>
                          <input type="time" name="work_start"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
                      </div>
                      <div>
                          <label class="block text-xs text-gray-500 mb-1.5">نهاية الدوام <span
                                  class="text-red-400">*</span></label>
                          <input type="time" name="work_end"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
                      </div>
                  </div>

                  {{-- البريك --}}
                  <div class="grid grid-cols-2 gap-3">
                      <div>
                          <label class="block text-xs text-gray-500 mb-1.5">بداية البريك</label>
                          <input type="time" name="break_start"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
                      </div>
                      <div>
                          <label class="block text-xs text-gray-500 mb-1.5">نهاية البريك</label>
                          <input type="time" name="break_end"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
                      </div>
                  </div>

                  {{-- مدة الكشف --}}
                  <div>
                      <label class="block text-xs text-gray-500 mb-1.5">مدة الكشف <span
                              class="text-red-400">*</span></label>
                      <select name="session_duration"
                          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition bg-white">
                          <option value="15">15 دقيقة</option>
                          <option value="20">20 دقيقة</option>
                          <option value="30" selected>30 دقيقة</option>
                          <option value="45">45 دقيقة</option>
                          <option value="60">60 دقيقة</option>
                      </select>
                  </div>

              </div>

              <div class="flex gap-2 px-5 pb-5">
                  <button type="submit"
                      class="flex-1 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium py-2.5 rounded-lg transition">
                      حفظ
                  </button>
                  <button type="button" @click="showAddModal = false"
                      class="flex-1 border border-gray-200 text-gray-600 text-sm py-2.5 rounded-lg hover:bg-gray-50 transition">
                      إلغاء
                  </button>
              </div>
          </form>
      </div>
  </div>
