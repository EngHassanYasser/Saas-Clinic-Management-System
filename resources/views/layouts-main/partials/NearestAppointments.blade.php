 <!-- Nearest Appointments -->
    <section class="pb-16">
        <div class="max-w-7xl mx-auto px-6">

            <div class="flex items-center justify-between mb-10">

                <h2 class="text-3xl font-black text-gray-900">
                    أقرب المواعيد المتاحة
                </h2>

            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

                @for ($i = 1; $i <= 3; $i++)

                    <div class="bg-white rounded-3xl p-6 border shadow-sm">

                        <div class="flex items-center gap-4 mb-5">

                            <img
                                src="https://randomuser.me/api/portraits/women/44.jpg"
                                class="w-16 h-16 rounded-2xl object-cover"
                                alt=""
                            >

                            <div>
                                <h3 class="font-black text-lg">
                                    د. سارة محمد
                                </h3>

                                <p class="text-gray-500">
                                    استشارية أطفال
                                </p>
                            </div>

                        </div>

                        <div class="flex items-center justify-between mb-5">

                            <div>
                                <p class="text-sm text-gray-400">التاريخ</p>
                                <p class="font-bold">اليوم</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-400">الوقت</p>
                                <p class="font-bold">7:30 مساءً</p>
                            </div>

                        </div>

                        <a
                            href="#"
                            class="block text-center bg-emerald-600 hover:bg-emerald-700 transition text-white py-3 rounded-2xl font-bold"
                        >
                            احجز الموعد
                        </a>

                    </div>

                @endfor
            </div>
        </div>
    </section>