<tbody class="divide-y divide-gray-100 bg-white">
    <template x-for="item in subscriptions" :key="item.id">
        <tr class="transition-colors duration-200 hover:bg-slate-50">
            <td class="px-6 py-5 font-medium text-gray-900" x-text="item.clinic.name"></td>

            <td class="px-6 py-5 text-gray-700 capitalize" x-text="item.plan.name"></td>

            <td class="px-6 py-5 font-medium text-gray-900 whitespace-nowrap"
                x-text="item.price + ' EGP'"></td>

            <td class="px-6 py-5 text-gray-600 whitespace-nowrap"
                x-text="item.startAt"></td>

            <td class="px-6 py-5 text-gray-600 whitespace-nowrap"
                x-text="item.endAt"></td>

            <td class="px-6 py-5">
                <span
                    class="inline-flex min-w-24 justify-center rounded-full px-3 py-1 text-xs font-medium"
                    :class="badgeClass(item.status)"
                    x-text="item.status">
                </span>
            </td>

            <td class="px-6 py-5">
                <div
                    x-show="statuses.CANCELLED != item.status"
                    class="flex items-center justify-center gap-2">

                    <button
                        @click="openEdit(item)"
                        class="rounded-md px-3 py-1.5 text-sm font-medium text-amber-600 transition hover:bg-amber-50 hover:text-amber-700">
                        تعديل
                    </button>

                    <form
                        x-show="[statuses.EXPIRED, statuses.PENDING].includes(item.status)"
                        :action="'{{ url('subscriptions') }}/renew/' + item.id"
                        method="POST">
                        @csrf
                        @method('PUT')

                        <button
                            type="submit"
                            class="rounded-md px-3 py-1.5 text-sm font-medium text-emerald-600 transition hover:bg-emerald-50 hover:text-emerald-700">
                            تجديد
                        </button>
                    </form>

                    <form
                        :action="'{{ url('subscriptions') }}/' + item.id + '/status/cancelled'"
                        method="POST">
                        @csrf
                        @method('PUT')

                        <button
                            type="submit"
                            class="rounded-md px-3 py-1.5 text-sm font-medium text-red-600 transition hover:bg-red-50 hover:text-red-700">
                            إلغاء
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    </template>
</tbody>