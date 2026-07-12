    <div class="flex justify-between items-center mb-6">

        <div>
            <h1 class="text-xl font-semibold text-gray-800">الشكاوى</h1>
            <p class="text-sm text-gray-500">إدارة ومتابعة شكاوى المرضى</p>
        </div>

        <a href="{{ route('complains.create') }}"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg shadow-sm transition">

            <!-- icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>

            <span>إضافة شكوى</span>
        </a>

    </div>