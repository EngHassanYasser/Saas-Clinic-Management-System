  {{-- ===== ADS GRID ===== --}}
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

      <template x-for="ad in filtered" :key="ad.id">
          <div
              class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all flex flex-col justify-between">

              <div>
                  <div class="flex items-start justify-between gap-2 mb-2">
                      <h3 class="font-bold text-gray-800 text-base leading-snug" x-text="ad.title"></h3>
                      <span class="flex-shrink-0 px-2.5 py-0.5 rounded-full text-xs font-medium"
                          :class="ad.status === 'active' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-600'"
                          x-text="ad.status === 'active' ? 'نشط' : 'غير نشط'">
                      </span>
                  </div>
                  <p class="text-gray-500 text-sm leading-relaxed" x-text="ad.desc"></p>
              </div>

              <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-gray-50">
                  <button @click="openEdit(ad)"
                      class="inline-flex items-center gap-1.5 text-xs font-medium text-amber-600 hover:text-amber-700 bg-amber-50 hover:bg-amber-100 px-3 py-1.5 rounded-lg transition">
                      <i class="fas fa-pen text-[10px]"></i> تعديل
                  </button>
                  <button @click="openDelete(ad)"
                      class="inline-flex items-center gap-1.5 text-xs font-medium text-red-500 hover:text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition">
                      <i class="fas fa-trash text-[10px]"></i> حذف
                  </button>
              </div>

          </div>
      </template>

      {{-- Empty State --}}
      <div x-show="filtered.length === 0" x-cloak
          class="col-span-full py-16 flex flex-col items-center justify-center text-center">
          <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-300 text-3xl mb-4">
              <i class="fas fa-bullhorn"></i>
          </div>
          <p class="text-gray-500 font-medium">لا توجد إعلانات</p>
          <p class="text-gray-400 text-sm mt-1">اضغط على "إضافة إعلان" لإنشاء إعلان جديد</p>
      </div>

  </div>
