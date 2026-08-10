<div x-show="showVacationModal" x-transition.opacity @click.self="closeModal()" x-cloak
    class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center" dir="rtl">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4 shadow-xl">
        <x-vacations.model.header />
        <x-vacations._form />
    </div>
</div>
