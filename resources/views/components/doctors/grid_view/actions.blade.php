<div class="flex items-center gap-2 mt-auto pt-3 border-t border-gray-50">

    {{-- تعديل --}}
    <button type="button" @click="openEdite(doctor)"
        class="flex-1 text-center text-xs text-teal-600 border border-teal-200 hover:bg-teal-50 py-1.5 rounded-lg transition">
        <i class="fa fa-pen ml-1"></i> تعديل
    </button>

    {{-- حذف --}}
    <div x-data="{ confirmDelete: false }">

        @csrf
        @method('DELETE')
        <div>
            <button type="button" x-show="!confirmDelete" @click="confirmDelete = true"
                class="w-8 h-8 flex items-center justify-center text-red-400 border border-red-200 hover:bg-red-50 rounded-lg transition">
                <i class="fa fa-trash text-xs"></i>
            </button>

            <div x-show="confirmDelete" class="flex items-center gap-1">
                <form :action="'{{ url('doctors') }}/' + doctor.id" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="text-xs text-white bg-red-500 hover:bg-red-600 px-2 py-1.5 rounded-lg transition">
                        تأكيد
                    </button>
                </form>

                <button type="button" @click="confirmDelete = false"
                    class="text-xs text-gray-500 border border-gray-200 hover:bg-gray-50 px-2 py-1.5 rounded-lg transition">
                    لأ
                </button>
            </div>
            <div>
            </div>
        </div>

    </div>
</div>
