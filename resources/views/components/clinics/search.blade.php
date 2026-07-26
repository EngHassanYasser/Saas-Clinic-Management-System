<div class="mt-4 mb-4 bg-white rounded-3xl p-5 border">
    <form action="#" method="GET">
        <div class="grid md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <input type="text" name="search" placeholder="ابحث باسم الدكتور أو العيادة أو التخصص"
                    class="w-full border border-gray-200 rounded-2xl px-5 py-4 focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <!-- Area -->
            <div>
                <select name="area"
                    class="w-full border border-gray-200 rounded-2xl px-5 py-4 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option>اختر المنطقة</option>
                    <option>السالمية</option>
                    <option>حولي</option>
                    <option>الفروانية</option>
                    <option>الجهراء</option>
                </select>
            </div>
            <!-- Button -->
            <div>
                <button type="submit"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 transition text-white rounded-2xl py-4 font-bold">
                    بحث
                </button>
            </div>
        </div>
    </form>
</div>
