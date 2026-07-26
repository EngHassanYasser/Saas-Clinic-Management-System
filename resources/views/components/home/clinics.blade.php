<section class="pb-16" id="clinics">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-3xl font-black text-gray-900">
                عيادات مميزة
            </h2>
            <a href="#" class="text-emerald-600 font-semibold">
                عرض الكل
            </a>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @for ($i = 1; $i <= 3; $i++)
                <div class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition border">
                    <img src="https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d"
                        class="w-full h-56 object-cover" alt="">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-black text-gray-900">
                                عيادة الحياة الطبية
                            </h3>
                            <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-sm">
                                مميزة
                            </span>
                        </div>
                        <p class="text-gray-500 mb-4">
                            السالمية - الكويت
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">
                                أقرب موعد اليوم 5:00 م
                            </span>
                            <a href="#"
                                class="bg-emerald-600 hover:bg-emerald-700 transition text-white px-5 py-3 rounded-2xl font-semibold">
                                احجز الآن
                            </a>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</section>
