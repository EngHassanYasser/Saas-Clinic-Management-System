<form
    :action="mode == 'add' ?
        '{{ route('vacations.store') }}' :
        '{{ url('vacations') }}/' + selectedVacation.id"
    method="POST">
    <template x-if="mode == 'update'">
        <input type="hidden" name="_method" value="PUT">
    </template>
    @csrf
    <div class="flex flex-col gap-4">
        <x-vacations.model.doctors />
        <x-vacations.model.status />
        <x-vacations.model.reason />
        <x-vacations.model.dates />
        <x-vacations.model.duration />
    </div>
    <x-vacations.model.actions />
</form>
