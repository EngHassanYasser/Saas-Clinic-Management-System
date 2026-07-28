<div class="grid gap-6 md:grid-cols-3">

    <!-- Total Plans -->

    <div
        class="group rounded-3xl border border-gray-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-indigo-200 hover:shadow-xl">

        <div class="flex items-start justify-between">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    إجمالي الباقات
                </p>

                <h2 class="mt-1 text-2xl font-extrabold text-gray-900" x-text="plans.length">
                </h2>

                <p class="mt-2 text-sm text-gray-400">
                    جميع الباقات الموجودة بالنظام
                </p>

            </div>

            <div
                class="flex h-5 w-14 items-center justify-center rounded-2xl bg-indigo-100 transition group-hover:bg-indigo-600">

                <i class="fa-solid fa-layer-group text-xl text-indigo-600 transition group-hover:text-white">
                </i>

            </div>

        </div>

    </div>

    <!-- Active Plans -->

    <div
        class="group rounded-3xl border border-green-100 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">

        <div class="flex items-start justify-between">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    الباقات النشطة
                </p>

                <h2 class="mt-3 text-4xl font-extrabold text-green-600"
                    x-text="plans.filter(plan => plan.status === 'active').length">
                </h2>

                <p class="mt-2 text-sm text-green-500">
                    تعمل حالياً ويمكن الاشتراك بها
                </p>

            </div>

            <div
                class="flex h-14 w-14 items-center justify-center rounded-2xl bg-green-100 transition group-hover:bg-green-600">

                <i class="fa-solid fa-circle-check text-xl text-green-600 transition group-hover:text-white">
                </i>

            </div>

        </div>

    </div>

    <!-- Inactive Plans -->

    <div
        class="group rounded-3xl border border-red-100 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">

        <div class="flex items-start justify-between">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    الباقات الموقوفة
                </p>

                <h2 class="mt-3 text-4xl font-extrabold text-red-600"
                    x-text="plans.filter(plan => plan.status === 'inactive').length">
                </h2>

                <p class="mt-2 text-sm text-red-500">
                    غير متاحة للاشتراكات الجديدة
                </p>

            </div>

            <div
                class="flex h-14 w-14 items-center justify-center rounded-2xl bg-red-100 transition group-hover:bg-red-600">

                <i class="fa-solid fa-ban text-xl text-red-600 transition group-hover:text-white">
                </i>

            </div>

        </div>

    </div>

</div>
