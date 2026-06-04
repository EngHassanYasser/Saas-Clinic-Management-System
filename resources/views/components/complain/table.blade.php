    {{-- ===================== TABLE ===================== --}}
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 text-xs text-gray-400 bg-gray-50">
                    <th class="text-right px-4 py-3 font-medium">المريض</th>
                    <th class="text-right px-4 py-3 font-medium">الموضوع</th>
                    <th class="text-right px-4 py-3 font-medium">ضد</th>
                    <th class="text-right px-4 py-3 font-medium">الأولوية</th>
                    <th class="text-right px-4 py-3 font-medium">الحالة</th>
                    <th class="text-right px-4 py-3 font-medium">التاريخ</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50" id="tableBody">
                @foreach ($complaints as $c)
                    @php
                        $st = $statusMap[$c['status']];
                        $pr = $priorityMap[$c['priority']];
                    @endphp
                    <tr class="complaint-row hover:bg-gray-50 transition cursor-pointer"
                        data-patient="{{ $c['patient'] }}" data-subject="{{ $c['subject'] }}"
                        data-status="{{ $c['status'] }}" data-priority="{{ $c['priority'] }}"
                        data-against="{{ $c['against'] }}"
                        data-date="{{ \Carbon\Carbon::parse($c['date'])->format('d/m/Y') }}"
                        data-initials="{{ $c['initials'] }}" data-color="{{ $c['color'] }}"
                        onclick="openDetails(this)">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="w-8 h-8 rounded-lg {{ $c['color'] }} flex items-center justify-center text-xs font-medium flex-shrink-0">
                                    {{ $c['initials'] }}
                                </div>
                                <span class="font-medium text-gray-700">{{ $c['patient'] }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600 max-w-xs truncate">{{ $c['subject'] }}</td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $c['against'] }}</td>
                        <td class="px-4 py-3">
                            <span
                                class="text-xs px-2.5 py-1 rounded-full {{ $pr['class'] }}">{{ $pr['label'] }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span
                                class="text-xs px-2.5 py-1 rounded-full {{ $st['class'] }}">{{ $st['label'] }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-400 text-xs">
                            {{ \Carbon\Carbon::parse($c['date'])->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3" onclick="event.stopPropagation()">
                            <div class="flex items-center gap-3">
                                <button onclick="openDetails(this.closest('tr'))"
                                    class="text-teal-500 hover:text-teal-700 transition">
                                    <i class="fa fa-eye text-xs"></i>
                                </button>
                                    <button onclick="confirmDelete(this)"
                                        class="text-red-400 hover:text-red-600 transition">
                                        <i class="fa fa-trash text-xs"></i>
                                    </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div id="emptyState" class="hidden flex-col items-center justify-center py-16 text-gray-300">
            <i class="fa fa-flag text-5xl mb-4"></i>
            <p class="text-sm">لا توجد شكاوى مطابقة</p>
        </div>
    </div>
