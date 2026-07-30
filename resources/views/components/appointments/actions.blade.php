<div class="flex flex-wrap gap-2 lg:flex-shrink-0">

    @if (Auth()->user()->type == \App\Enums\RoleType::CLINIC->value)
        <!-- Reschedule -->
        <button type="button" @click="openReschedule(appointment)"
            class="px-3 py-2 rounded-xl bg-blue-50 border border-blue-100 text-blue-700 text-xs font-semibold hover:bg-blue-100 transition whitespace-nowrap">
            <i class="fas fa-calendar-alt"></i> إعادة جدولة
        </button>
        <!-- Complete -->
        <form :action="'/appointments/' + appointment.id + '/status'" method="POST">
            @csrf
            <input type="hidden" name="_method" value="PATCH">
            <input type="hidden" name="status" value="completed" />
            <button type="submit"
                class="px-3 py-2 rounded-xl bg-green-50 border border-green-100 text-green-700 text-xs font-semibold hover:bg-green-100 transition whitespace-nowrap">
                <i class="fas fa-check-circle"></i> اكتملت
            </button>
        </form>
    @endif
    <form :action="'/appointments/' + appointment.id + '/status'" method="POST">
        @csrf
        <input type="hidden" name="_method" value="PATCH">
        <input type="hidden" name="status" value="cancelled" />
        <button type="submit"
            class="px-3 py-2 rounded-xl bg-red-50 border border-red-100 text-red-700 text-xs font-semibold hover:bg-red-100 transition whitespace-nowrap">
            <i class="fas fa-times"></i> إلغاء
        </button>
    </form>
</div>
