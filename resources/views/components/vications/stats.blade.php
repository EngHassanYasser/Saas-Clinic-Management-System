  {{-- ===================== STATS ===================== --}}
  <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">

      <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-3">

          <div class="w-10 h-10 rounded-lg bg-teal-50 flex items-center justify-center flex-shrink-0">
              <i class="fa fa-umbrella-beach text-teal-600"></i>
          </div>

          <div>

              <p class="text-xs text-gray-400">
                  إجمالي الإجازات
              </p>

              <p class="text-xl font-medium text-gray-800" x-text="totalVacations">
              </p>

          </div>

      </div>

      <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-3">

          <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
              <i class="fa fa-clock text-amber-500"></i>
          </div>

          <div>

              <p class="text-xs text-gray-400">
                  جارية الآن
              </p>

              <p class="text-xl font-medium text-gray-800" x-text="activeCount">
              </p>

          </div>

      </div>

      <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-3">

          <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
              <i class="fa fa-calendar-days text-blue-500"></i>
          </div>

          <div>

              <p class="text-xs text-gray-400">
                  قادمة
              </p>

              <p class="text-xl font-medium text-gray-800" x-text="upcomingCount">
              </p>

          </div>

      </div>

      <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-3">

          <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center flex-shrink-0">
              <i class="fa fa-circle-check text-gray-400"></i>
          </div>

          <div>

              <p class="text-xs text-gray-400">
                  منتهية
              </p>

              <p class="text-xl font-medium text-gray-800" x-text="endedCount">
              </p>

          </div>

      </div>

  </div>
