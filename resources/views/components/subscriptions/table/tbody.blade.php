<tbody class="divide-y divide-gray-100">
    <template x-for="item in subscriptions" :key="item.id">
        <tr class="hover:bg-gray-50 transition">
            <td class="p-4 font-medium" x-text="item.clinic.name"></td>
            <td class="p-4 capitalize" x-text="item.plan.name"></td>
            <td class="p-4" x-text="item.price + ' EGP'"></td>
            <td class="p-4" x-text="item.start_at"></td>
            <td class="p-4" x-text="item.end_at"></td>
            <td class="p-4">
                <span class="px-3 py-1 text-xs rounded-full" :class="badgeClass(getStatus(item.status))"
                    x-text="statusLabel(getStatus(item.status))"></span>
            </td>
            <td class="p-4 text-center space-x-2 space-x-reverse">
                <button @click="openEdit(item)"
                    class="text-amber-600 hover:text-amber-700 transition font-medium">تعديل</button>
                <form :action="'{{ url('subscriptions') }}/renew/' + item.id" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="text-red-600 hover:text-red-700 transition font-medium">تجديد</button>
                </form>
                <form :action="'{{ url('subscriptions') }}/' + item.id + '/status/cancelled'" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="text-red-600 hover:text-red-700 transition font-medium">الغاء</button>
                </form>
            </td>
        </tr>
    </template>
</tbody>
