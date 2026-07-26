<div x-show="toast.show" x-cloak x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-4"
    class="fixed bottom-6 left-6 z-[60] flex items-center gap-3 text-white text-sm px-4 py-3 rounded-xl shadow-lg"
    :class="toast.bg">
    <i class="fas text-base" :class="toast.icon"></i>
    <span x-text="toast.msg"></span>
</div>
