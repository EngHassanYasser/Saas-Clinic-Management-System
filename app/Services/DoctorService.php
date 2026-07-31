<?php

namespace App\Services;

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Doctor_service_price;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoctorService
{
    public function getDoctorsNames(?int $clinicId): Collection
    {
        if ($clinicId === null) {
            return new Collection();
        }
        return Doctor::select('id', 'name')
            ->whereRelation('clinics', 'clinic_id', $clinicId)->get();
    }
    public function getAll(): Collection
    {
        $clinic = Clinic::with([
            'doctors.specialities',
            'doctors.servicePrices.clinic_service',
            'doctors.media',
        ])->findOrFail(Auth::user()->clinic_id);
        return $clinic->doctors->map(function ($doctor) use ($clinic) {
            return [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'phone' => $doctor->phone,
                'email' => $doctor->email,
                'Consultation_Fee' => optional(
                    $doctor->servicePrices
                        ->first(fn($item) => $item->clinic_service?->name === 'كشف')
                )->price ?? 'لا توجد خدمة',

                'speciality' => optional($doctor->specialities->first())
                    ->only(['id', 'name']),

                'is_active' => $doctor->clinics
                    ->firstWhere('id', $clinic->id)
                    ?->pivot
                    ?->is_active,

                'image' => $doctor->getFirstMediaUrl('avatar')
                    ?: asset('storage/default_profile_image.jpg'),
            ];
        });
    }
    public function addNew(array $data): Doctor
    {
        return  DB::transaction(function () use ($data) {

            $doctor = Doctor::create([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
            ]);
            $doctor->specialities()->attach([$data['speciality_id']]);
            $doctor->clinics()->attach([Auth::User()->clinic_id]);
            if (!empty($data['image'])) {
                $doctor->addMedia($data['image'])
                    ->toMediaCollection('avatar');
            }
            return $doctor;
        });
    }
    public function deleteById(int $id): bool
    {
        return DB::transaction(function () use ($id) {

            $doctor = Doctor::findOrFail($id);

            $doctor->clinics()->detach(Auth::user()->clinic_id);

            Doctor_service_price::where('doctor_id', $id)->delete();

            $doctor->specialities()->detach();

            $deleted = $doctor->delete();

            return $deleted;
        });
    }
    public function update(array $data, int $id): bool
    {
        return DB::transaction(function () use ($data, $id) {

            $doctor = Doctor::findOrFail($id);

            $updated = $doctor->update([
                'name'  => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
            ]);

            $doctor->specialities()->sync([$data['speciality_id']]);
            $doctor->clinics()->updateExistingPivot(
                Auth::user()->clinic_id,
                [
                    'is_active' => $data['is_active'],
                ]
            );
            if (!empty($data['image'])) {

                $doctor->clearMediaCollection('avatar');

                $doctor->addMedia($data['image'])
                    ->toMediaCollection('avatar');
            }

            return $updated;
        });
    }
    public function getStats(?int $clinicId): Doctor
    {
        return Doctor::query()
            ->join('clinic_doctors', 'doctors.id', '=', 'clinic_doctors.doctor_id')
            ->leftJoin('doctor_speciality', 'doctors.id', '=', 'doctor_speciality.doctor_id')
            ->where('clinic_doctors.clinic_id', $clinicId)
            ->selectRaw("
        COUNT(DISTINCT doctors.id) as total,
        COUNT(DISTINCT CASE WHEN clinic_doctors.is_active = 1 THEN doctors.id END) as active,
        COUNT(DISTINCT CASE WHEN clinic_doctors.is_active = 0 THEN doctors.id END) as inactive,
        COUNT(DISTINCT doctor_speciality.speciality_id) as specialities
        ")->first();
    }
    public function getAvailableDoctors(int $clinicId, int $specialityId, int $serviceId): Collection
    {
        return Doctor::select('doctors.id', 'doctors.name')
            ->join('doctor_speciality', 'doctor_speciality.doctor_id', '=', 'doctors.id')
            ->join('doctor_service_prices', 'doctor_service_prices.doctor_id', '=', 'doctors.id')
            ->where('doctor_speciality.speciality_id', $specialityId)
            ->where('doctor_service_prices.clinic_service_id', $serviceId)
            ->where('doctor_service_prices.clinic_id', $clinicId)
            ->distinct()
            ->get();
    }
}
