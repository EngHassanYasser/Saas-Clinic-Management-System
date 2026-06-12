{{-- ===== EDIT MODAL ===== --}}
<div x-show="showEditModal"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
     @keydown.escape.window="showEditModal = false">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden"
         @click.outside="showEditModal = false">

        {{-- HEADER --}}
        <div class="flex items-center justify-between px-6 py-4 border-b bg-gray-50">
            <h2 class="font-semibold text-gray-800 text-sm">
                تعديل بيانات الطبيب
            </h2>

            <button type="button"
                    @click="showEditModal = false"
                    class="text-gray-400 hover:text-red-500 transition">
                <i class="fa fa-xmark"></i>
            </button>
        </div>

        {{-- FORM --}}
        <template x-if="editDoctor">
            <form :action="`/doctors/${editDoctor.id}`"
                  method="POST"
                  enctype="multipart/form-data"
                  class="p-6 flex flex-col gap-5">

                @csrf
                @method('PUT')

                {{-- IMAGE UPLOAD --}}
                <div class="flex flex-col items-center gap-3">

                    <label class="text-xs text-gray-500 self-start">
                        صورة الطبيب
                    </label>

                    {{-- avatar --}}
                    <div class="relative w-28 h-28">

                        {{-- image or icon --}}
                        <div class="w-28 h-28 rounded-full border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden bg-gray-50 hover:border-teal-400 transition">

                            <template x-if="imagePreview">
                                <img :src="imagePreview"
                                     class="w-full h-full object-cover">
                            </template>

                            <template x-if="!imagePreview">
                                <i class="fa fa-user text-gray-300 text-3xl"></i>
                            </template>

                        </div>

                        {{-- edit button --}}
                        <label class="absolute bottom-0 right-0 bg-teal-600 text-white w-8 h-8 flex items-center justify-center rounded-full cursor-pointer hover:bg-teal-700 transition shadow">
                            <i class="fa fa-camera text-xs"></i>

                            <input type="file"
                                   name="image"
                                   accept="image/*"
                                   class="hidden"
                                   @change="
                                        const file = $event.target.files[0];
                                        if (file) {
                                            imagePreview = URL.createObjectURL(file);
                                        }
                                   ">
                        </label>

                    </div>

                    <p class="text-[11px] text-gray-400">
                        اضغط على الكاميرا لتغيير الصورة
                    </p>
                </div>

                {{-- FIELDS --}}
                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="text-xs text-gray-500">اسم الطبيب</label>
                        <input type="text"
                               name="name"
                               :value="editDoctor.name"
                               class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-100 outline-none transition">
                    </div>

                    <div>
                        <label class="text-xs text-gray-500">التخصص</label>
                        <input type="text"
                               name="specialty"
                               :value="editDoctor.specialty"
                               class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-100 outline-none transition">
                    </div>

                    <div>
                        <label class="text-xs text-gray-500">الموبايل</label>
                        <input type="text"
                               name="phone"
                               :value="editDoctor.phone"
                               class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-100 outline-none transition">
                    </div>

                    <div>
                        <label class="text-xs text-gray-500">الإيميل</label>
                        <input type="email"
                               name="email"
                               :value="editDoctor.email"
                               class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-teal-500 focus:ring-1 focus:ring-teal-100 outline-none transition">
                    </div>

                </div>

                {{-- ACTIONS --}}
                <div class="flex gap-3 pt-2">

                    <button type="submit"
                            class="flex-1 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium py-2.5 rounded-lg transition shadow-sm">
                        حفظ التعديلات
                    </button>

                    <button type="button"
                            @click="showEditModal = false;imagePreview=null"
                            class="flex-1 border border-gray-200 text-gray-600 text-sm py-2.5 rounded-lg hover:bg-gray-50 transition">
                        إلغاء
                    </button>

                </div>

            </form>
        </template>

    </div>
</div>