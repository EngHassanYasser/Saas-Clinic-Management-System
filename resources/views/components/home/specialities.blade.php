<section class="py-16" id="specialities">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-3xl font-black text-gray-900">
                التخصصات الطبية
            </h2>
            <a href="#" class="text-emerald-600 font-semibold">
                عرض الكل
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-5">
            @foreach (['جلدية', 'أسنان', 'أطفال', 'عظام', 'باطنية', 'قلب'] as $specialty)
                <div
                    class="bg-white rounded-3xl p-6 text-center shadow-sm hover:shadow-lg transition cursor-pointer border">
                    <div class="w-16 h-16 bg-emerald-100 rounded-2xl mx-auto mb-4 flex items-center justify-center">
                        🩺
                    </div>

                    <h3 class="font-bold text-gray-800">
                        {{ $specialty }}
                    </h3>
                </div>
            @endforeach
        </div>
    </div>
</section>
