<span class="text-xs px-2.5 py-1 rounded-full"
    :class="doctor.is_active ?
        'bg-emerald-100 text-emerald-700' :
        'bg-gray-100 text-gray-500'">
    <span x-text="doctor.is_active? 'متاح':' غير متاح'"></span>
</span>
