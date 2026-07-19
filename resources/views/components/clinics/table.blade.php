  {{-- ===== TABLE ===== --}}
  <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
      <div class="overflow-x-auto">
          <table class="w-full text-sm">
              <thead>
                  <tr class="bg-gray-50 border-b border-gray-100">
                      <th class="py-3.5 px-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">
                          العيادة</th>
                      <th class="py-3.5 px-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">
                          المدينة</th>
                      <th class="py-3.5 px-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">
                          الحالة</th>
                      <th class="py-3.5 px-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">
                          الباقة</th>
                      <th class="py-3.5 px-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">
                          تاريخ الانضمام</th>
                      <th class="py-3.5 px-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">
                          الإجراءات</th>
                  </tr>
              </thead>
              <tbody class="divide-y divide-gray-50">
                  <template x-for="(c, i) in paginated" :key="c.id">
                      <tr class="hover:bg-gray-50/70 transition-colors">

                          <td class="py-3.5 px-4">
                              <div class="flex items-center gap-3">
                                  <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-sm flex-shrink-0"
                                      :class="avatarClass(c.name)" x-text="avatarLetter(c.name)"></div>
                                  <div>
                                      <p class="font-medium text-gray-800" x-text="c.name"></p>
                                      <p class="text-xs text-gray-400" x-text="c.email"></p>
                                  </div>
                              </div>
                          </td>

                          <td class="py-3.5 px-4 text-gray-600 text-sm">
                              <span class="inline-flex items-center gap-1">
                                  <i class="fas fa-location-dot text-gray-300 text-xs"></i>
                                  <span x-text="c.city"></span>
                              </span>
                          </td>

                          <td class="py-3.5 px-4">
                              <span
                                  class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full"
                                  :class="statusBadgeClass(c.status)">
                                  <span class="w-1.5 h-1.5 rounded-full inline-block"
                                      :class="statusDotClass(c.status)"></span>
                                  <span x-text="c.status"></span>
                              </span>
                          </td>

                          <td class="py-3.5 px-4">
                              <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full"
                                  :class="planBadgeClass(c.plan)">
                                  <i class="fas text-[10px]" :class="planIcon(c.plan)"></i>
                                  <span x-text="c.plan"></span>
                              </span>
                          </td>

                          <td class="py-3.5 px-4 text-gray-400 text-xs" x-text="c.joined_at"></td>

                          <td class="py-3.5 px-4">
                              <div class="flex items-center justify-center gap-2">

                                  <button @click="openEdit(c)" title="تعديل"
                                      class="w-8 h-8 rounded-lg bg-gray-50 hover:bg-amber-50 hover:text-amber-600 text-gray-400 flex items-center justify-center transition border border-gray-100">
                                      <i class="fas fa-pen text-xs"></i>
                                  </button>

                                  <button @click="toggleStatus(c.id)"
                                      :title="c.status === 'موقوف' ? 'تفعيل' : 'إيقاف'"
                                      class="w-8 h-8 rounded-lg bg-gray-50 text-gray-400 flex items-center justify-center transition border border-gray-100"
                                      :class="c.status === 'موقوف' ? 'hover:bg-green-50 hover:text-green-600' :
                                          'hover:bg-red-50 hover:text-red-500'">
                                      <i class="fas text-xs"
                                          :class="c.status === 'موقوف' ? 'fa-circle-check' : 'fa-ban'"></i>
                                  </button>

                                  <button @click="openDelete(c)" title="حذف"
                                      class="w-8 h-8 rounded-lg bg-gray-50 hover:bg-red-50 hover:text-red-500 text-gray-400 flex items-center justify-center transition border border-gray-100">
                                      <i class="fas fa-trash text-xs"></i>
                                  </button>

                              </div>
                          </td>

                      </tr>
                  </template>
              </tbody>
          </table>
      </div>

      {{-- Empty State --}}
      <div x-show="filtered.length === 0" x-cloak class="py-16 flex flex-col items-center justify-center text-center">
          <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-300 text-3xl mb-4">
              <i class="fas fa-hospital-slash"></i>
          </div>
          <p class="text-gray-500 font-medium">لا توجد نتائج</p>
          <p class="text-gray-400 text-sm mt-1">جرّب تغيير معايير البحث</p>
      </div>

      {{-- Pagination --}}
      <div class="flex flex-wrap items-center justify-between gap-3 px-4 py-3 border-t border-gray-100 bg-gray-50">

          <p class="text-xs text-gray-500" x-text="paginationInfo"></p>

          <div class="flex items-center gap-1">

              <button @click="currentPage--" :disabled="currentPage === 1"
                  class="w-8 h-8 rounded-lg border border-gray-200 bg-white text-gray-400 hover:bg-gray-100 flex items-center justify-center transition disabled:opacity-30 disabled:cursor-not-allowed">
                  <i class="fas fa-chevron-right text-xs"></i>
              </button>

              <template x-for="p in totalPages" :key="p">
                  <button @click="currentPage = p"
                      class="w-8 h-8 rounded-lg text-xs font-medium flex items-center justify-center transition"
                      :class="p === currentPage ? 'bg-blue-600 text-white' :
                          'border border-gray-200 bg-white text-gray-600 hover:bg-gray-100'"
                      x-text="p">
                  </button>
              </template>

              <button @click="currentPage++" :disabled="currentPage === totalPages"
                  class="w-8 h-8 rounded-lg border border-gray-200 bg-white text-gray-400 hover:bg-gray-100 flex items-center justify-center transition disabled:opacity-30 disabled:cursor-not-allowed">
                  <i class="fas fa-chevron-left text-xs"></i>
              </button>

          </div>

      </div>
  </div>
