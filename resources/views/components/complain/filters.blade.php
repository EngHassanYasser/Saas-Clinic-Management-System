        {{-- ===================== FILTERS ===================== --}}
        <div class="bg-white rounded-xl border border-gray-100 p-4 mb-6 flex flex-wrap items-center gap-3">

            <div class="relative flex-1 min-w-48">
                <i class="fa fa-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                <input type="text" id="searchInput" placeholder="ابحث بالاسم أو الموضوع..."
                    class="w-full border border-gray-200 rounded-lg pr-9 pl-3 py-2 text-sm text-gray-700 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
            </div>

            <select id="statusFilter"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 focus:outline-none focus:border-teal-400 bg-white min-w-36">
                <option value="">كل الحالات</option>
                <option value="pending">في الانتظار</option>
                <option value="reviewing">قيد المراجعة</option>
                <option value="resolved">تم الحل</option>
            </select>

            <select id="priorityFilter"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 focus:outline-none focus:border-teal-400 bg-white min-w-32">
                <option value="">كل الأولويات</option>
                <option value="urgent">عاجل</option>
                <option value="normal">عادي</option>
            </select>

        </div>
