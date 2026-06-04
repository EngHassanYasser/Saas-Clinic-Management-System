  {{-- ===== HEADER ===== --}}
  <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
      <div>
          <h1 class="text-2xl font-bold text-gray-900">إدارة الاشتراكات</h1>
          <p class="text-sm text-gray-500 mt-1">متابعة الاشتراكات، التجديد، الخطط، والمدفوعات</p>
      </div>

      <button @click="openAdd()"
          class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl shadow-sm flex items-center gap-2 transition">
          <i class="fas fa-plus"></i>
          إضافة اشتراك
      </button>
  </div>
