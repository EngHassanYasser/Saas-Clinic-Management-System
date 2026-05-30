    {{-- ===== CANCEL CONFIRM MODAL ===== --}}
    <div id="cancel-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4"
        onclick="closeCancelModal(event)">

        <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm" onclick="event.stopPropagation()">

            <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
            </div>

            <h3 class="text-center font-bold text-gray-900 text-lg mb-2">تأكيد إلغاء الموعد</h3>
            <p class="text-center text-gray-500 text-sm mb-1">
                هل أنت متأكد من إلغاء هذا الموعد؟
            </p>
            <p class="text-center text-amber-600 text-xs bg-amber-50 rounded-xl px-3 py-2 mt-3 border border-amber-100">
                <i class="fas fa-info-circle ml-1"></i>
                إذا كان الإلغاء قبل أقل من 6 ساعات سيتم خصم جزء من العربون
            </p>

            <div class="flex gap-3 mt-5">
                <button onclick="closeCancelModal()"
                    class="flex-1 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 font-semibold text-sm transition">
                    تراجع
                </button>

                <form id="cancel-form" method="POST" action="" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="w-full py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white font-semibold text-sm transition">
                        تأكيد الإلغاء
                    </button>
                </form>
            </div>

        </div>
    </div>

    <script>
        function confirmCancel(id) {
            document.getElementById('cancel-form').action = `/appointments/${id}/cancel`;
            const modal = document.getElementById('cancel-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeCancelModal(e) {
            if (e && e.target !== document.getElementById('cancel-modal')) return;
            const modal = document.getElementById('cancel-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>