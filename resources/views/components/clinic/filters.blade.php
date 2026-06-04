  {{-- ===== FILTERS ===== --}}
  <div class="bg-white rounded-xl border border-gray-100 p-4 mb-4 flex flex-wrap gap-3 items-center">

      <div class="relative flex-1 min-w-[200px]">
          <span class="absolute top-1/2 -translate-y-1/2 right-3 text-gray-400 text-sm">
              <i class="fas fa-magnifying-glass"></i>
          </span>
          <input x-model="search" @input="currentPage = 1" type="text" placeholder="بحث باسم العيادة أو المدينة..."
              class="w-full border border-gray-200 bg-gray-50 text-sm pr-9 pl-3 py-2.5 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300 transition">
      </div>

      <div class="relative">
          <span class="absolute top-1/2 -translate-y-1/2 right-3 text-gray-400 text-sm pointer-events-none">
              <i class="fas fa-filter"></i>
          </span>
          <select x-model="filterStatus" @change="currentPage = 1"
              class="border border-gray-200 bg-gray-50 text-sm pr-9 pl-8 py-2.5 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300 appearance-none transition">
              <option value="">الكل</option>
              <option value="نشط">نشط</option>
              <option value="موقوف">موقوف</option>
              <option value="قيد المراجعة">قيد المراجعة</option>
          </select>
          <span class="absolute top-1/2 -translate-y-1/2 left-3 text-gray-400 text-xs pointer-events-none">
              <i class="fas fa-chevron-down"></i>
          </span>
      </div>

      <div class="relative">
          <span class="absolute top-1/2 -translate-y-1/2 right-3 text-gray-400 text-sm pointer-events-none">
              <i class="fas fa-crown"></i>
          </span>
          <select x-model="filterPlan" @change="currentPage = 1"
              class="border border-gray-200 bg-gray-50 text-sm pr-9 pl-8 py-2.5 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300 appearance-none transition">
              <option value="">كل الباقات</option>
              <option value="Premium">Premium</option>
              <option value="Basic">Basic</option>
              <option value="Trial">Trial</option>
          </select>
          <span class="absolute top-1/2 -translate-y-1/2 left-3 text-gray-400 text-xs pointer-events-none">
              <i class="fas fa-chevron-down"></i>
          </span>
      </div>

      <button @click="search = ''; filterStatus = ''; filterPlan = ''; currentPage = 1"
          class="inline-flex items-center gap-2 text-sm text-gray-500 border border-gray-200 bg-gray-50 hover:bg-gray-100 px-4 py-2.5 rounded-lg transition">
          <i class="fas fa-rotate-right"></i>
          إعادة ضبط
      </button>

  </div>
