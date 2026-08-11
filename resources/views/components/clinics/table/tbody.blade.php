<tbody class="divide-y divide-gray-50">
    <template x-for="(c, i) in paginated" :key="c.id">
        <tr class="hover:bg-gray-50/70 transition-colors">
            <td class="py-3.5 px-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-sm flex-shrink-0"
                        :class="avatarClass(c.name)" x-text="avatarLetter(c.name)"></div>
                    <div>
                        <p class="font-medium text-gray-800" x-text="c.name"></p>
                        <p class="text-xs text-gray-400" x-text="c.email"></p>
                    </div>
                </div>
            </td>
            <td class="py-3.5 px-4 text-gray-600 text-sm">
                <span class="inline-flex items-center gap-1">
                    <i class="fas fa-location-dot text-gray-300 text-xs"></i>
                    <span x-text="c.city.name"></span>
                </span>
            </td>
            <td class="px-4 py-3.5">
                <span class="text-sm font-small text-gray-600" x-text="c.address"></span>
            </td>
            <td class="py-3.5 px-4">
                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full"
                    :class="statusBadgeClass(c.status)">
                    <span class="w-1.5 h-1.5 rounded-full inline-block" :class="statusDotClass(c.status)"></span>
                    <span x-text="c.status"></span>
                </span>
            </td>
            <td class="py-3.5 px-4">
                <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full"
                    :class="planBadgeClass(c.plan.name)">
                    <i class="fas text-[10px]" :class="planIcon(c.plan.name)"></i>
                    <span x-text="c.plan.name"></span>
                </span>
            </td>
            <td class="py-3.5 px-4 text-gray-400 text-xs" x-text="c.joinedAt"></td>
            <td class="py-3.5 px-4">
                <div class="flex items-center justify-center gap-2">

                    <button @click="openEdit(c)" title="تعديل"
                        class="w-8 h-8 rounded-lg bg-gray-50 hover:bg-amber-50 hover:text-amber-600 text-gray-400 flex items-center justify-center transition border border-gray-100">
                        <i class="fas fa-pen text-xs"></i>
                    </button>
                    <form :action="'{{ url('clinics') }}/' + c.id " method="POST">
                        @csrf()
                            <input type="hidden" name="_method" value="DELETE"/>
                    <button type="submit" title="حذف"
                        class="w-8 h-8 rounded-lg bg-gray-50 hover:bg-red-50 hover:text-red-500 text-gray-400 flex items-center justify-center transition border border-gray-100">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                    </form>
                </div>
            </td>
        </tr>
    </template>
</tbody>
