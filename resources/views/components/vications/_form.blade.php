<form
    :action="mode == 'add' ?
        '{{ route('vications.store') }}' :
        '{{ url('vications') }}/' + selectedVacation.id"
    method="POST">
    <template x-if="mode == 'update'">
        <input type="hidden" name="_method" value="PUT">
    </template>
    @csrf

    <div class="flex flex-col gap-4">

        <x-vications.model.doctors />
        <x-vications.model.status />

        <x-vications.model.reason />

        <x-vications.model.dates />

        <x-vications.model.duration />
    </div>
    <x-vications.model.actions />
</form>
