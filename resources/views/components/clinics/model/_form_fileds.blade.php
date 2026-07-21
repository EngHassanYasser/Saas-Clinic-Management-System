<div class="p-4 px-4 grid grid-cols-4 gap-2">
    @csrf
    <x-clinics.model.clinic_name />
    <x-clinics.model.email />
    <x-clinics.model.user_name />
    <x-clinics.model.owner_full_name />
    <x-clinics.model.password/>
    <x-clinics.model.password_confirmation/>
    <x-clinics.model.address />
    <x-clinics.model.phone/>
    <x-clinics.model.city />
    <x-clinics.model.status />
    <x-clinics.model.plan />
    <x-clinics.model.gendor/>
    <template x-if="mode== 'update'">
        <input type="hidden" name="_method" value="PUT">
    </template>
</div>
<x-clinics.model.validation_error />
