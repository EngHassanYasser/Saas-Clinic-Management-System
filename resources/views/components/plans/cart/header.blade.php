<div
    class="relative overflow-hidden rounded-t-3xl bg-gradient-to-br from-indigo-600 via-indigo-500 to-violet-500 p-6 text-white">

    <!-- Decoration -->
    <div class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-white/10"></div>
    <div class="absolute -left-6 -bottom-6 h-20 w-20 rounded-full bg-white/10"></div>

    <div class="relative flex items-start justify-between">

        <div>

            <div class="flex items-center gap-3">

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/15 backdrop-blur">

                    <i class="fa-solid fa-layer-group text-lg"></i>

                </div>

                <div>

                    <h2 class="text-2xl font-bold tracking-tight" x-text="plan.name">
                    </h2>

                    <p class="mt-1 text-sm text-indigo-100">

                        Monthly Subscription

                    </p>

                </div>

            </div>

            <div class="mt-8 flex items-end gap-2">

                <span class="text-5xl font-extrabold leading-none" x-text="plan.monthlyPrice">
                </span>

                <div class="pb-1">

                    <div class="text-lg font-semibold">
                        EGP
                    </div>

                    <div class="text-xs text-indigo-100">
                        / Month
                    </div>

                </div>

            </div>

        </div>

        <span class="rounded-full border border-white/20 bg-white/15 px-3 py-1 text-xs font-semibold backdrop-blur"
            :class="plan.status === 'active' ?
                'text-green-100' :
                'text-red-300'"
            x-text="plan.status">
        </span>

    </div>

</div>
