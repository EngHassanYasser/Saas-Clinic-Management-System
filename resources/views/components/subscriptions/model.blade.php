<div x-show="showModal" x-cloak x-transition.opacity
    class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50" @click.self="showModal = false">
    <div x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6">
        <h2 class="text-lg font-bold mb-5 flex items-center gap-2">
            <i class="fas fa-file-contract text-blue-600"></i>
            <span x-text="mode=='update' ? 'تعديل الاشتراك' : 'إضافة اشتراك'"></span>
        </h2>
        <form :action="{{ url('/subscriptions/') }}" method="POST">
            @csrf
            <div class="space-y-3">
                {{-- <button type="button" @click="console.log(form)">click</button> --}}

                <x-subscriptions.model.clinic_name />
                <x-subscriptions.model.plans />
                <x-subscriptions.model.duration />
            </div>
            <x-subscriptions.model.actions />
        </form>
    </div>
</div>
