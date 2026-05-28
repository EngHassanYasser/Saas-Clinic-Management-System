    <!-- Famous Doctors -->
    <section class="pb-16">
        <div class="max-w-7xl mx-auto px-6">

            <div class="flex items-center justify-between mb-10">
                <h2 class="text-3xl font-black text-gray-900">
                    أطباء مشهورون
                </h2>

                <a href="#" class="text-emerald-600 font-semibold">
                    عرض الكل
                </a>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">

                @for ($i = 1; $i <= 4; $i++)

                    <div class="bg-white rounded-3xl p-6 shadow-sm hover:shadow-xl transition border text-center">

                        <img
                            src="https://randomuser.me/api/portraits/men/32.jpg"
                            class="w-28 h-28 rounded-full mx-auto object-cover mb-5"
                            alt=""
                        >

                        <h3 class="text-xl font-black text-gray-900 mb-2">
                            د. أحمد السالم
                        </h3>

                        <p class="text-emerald-600 font-semibold mb-3">
                            استشاري جلدية
                        </p>

                        <p class="text-gray-500 text-sm mb-6">
                            عيادة الحياة الطبية
                        </p>

                        <a
                            href="#"
                            class="block bg-emerald-600 hover:bg-emerald-700 transition text-white py-3 rounded-2xl font-bold"
                        >
                            عرض المواعيد
                        </a>

                    </div>

                @endfor

            </div>

        </div>
    </section>