    {{-- ===================== LIST VIEW ===================== --}}
    <div id="listContainer" class="hidden bg-white rounded-xl border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 text-xs text-gray-400 bg-gray-50">
                    <th class="text-right px-4 py-3 font-medium">الطبيب</th>
                    <th class="text-right px-4 py-3 font-medium">التخصص</th>
                    <th class="text-right px-4 py-3 font-medium">السعر</th>
                    <th class="text-right px-4 py-3 font-medium">المدة</th>
                    <th class="text-right px-4 py-3 font-medium">أيام العمل</th>
                    <th class="text-right px-4 py-3 font-medium">المواعيد</th>
                    <th class="text-right px-4 py-3 font-medium">الحالة</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($doctors as $doctor)
                    <tr class="doctor-row hover:bg-gray-50 transition" data-name="{{ $doctor['name'] }}"
                        data-specialty="{{ $doctor['specialty'] }}"
                        data-status="{{ $doctor['active'] ? 'active' : 'inactive' }}">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="w-8 h-8 rounded-lg {{ $doctor['color'] }} flex items-center justify-center text-xs font-medium flex-shrink-0">
                                    {{ $doctor['initials'] }}
                                </div>
                                <span class="font-medium text-gray-700">{{ $doctor['name'] }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-teal-600 text-xs">{{ $doctor['specialty'] }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $doctor['price'] }} ج.م</td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $doctor['duration'] }} دقيقة</td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $doctor['days'] }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $doctor['appointments'] }}</td>
                        <td class="px-4 py-3">
                            <span
                                class="text-xs px-2.5 py-1 rounded-full {{ $doctor['active'] ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $doctor['active'] ? 'متاح' : 'غير متاح' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <a href="#" class="text-teal-500 hover:text-teal-700 transition"><i
                                        class="fa fa-pen text-xs"></i></a>
                                <a href="#" class="text-blue-500 hover:text-blue-700 transition"><i
                                        class="fa fa-eye text-xs"></i></a>
                                <button onclick="confirmDelete(this)"
                                    class="text-red-400 hover:text-red-600 transition"><i
                                        class="fa fa-trash text-xs"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
