<div class="mb-6">
    <div class="flex items-start justify-between gap-4">
        <!-- Title -->
        <div>
            <h2 class="text-2xl font-bold text-gray-900">
                خدمات العيادة
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                إدارة الخدمات والأسعار والأطباء
            </p>
        </div>
        <!-- Button -->
        <button @click="openCreate()"
            class="bg-teal-600 hover:bg-teal-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition whitespace-nowrap">
            + إضافة خدمة
        </button>
    </div>
    <!-- Alerts -->
    <div class="mt-4 space-y-2">
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc pr-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
    </div>
</div>
