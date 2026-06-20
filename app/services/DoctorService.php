<?php

namespace App\services;

use App\Models\doctor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class DoctorService
{
    public function getAll()
    {
       return doctor::with('specialities')->get()->map(function ($doctor) {
            return [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'phone' => $doctor->phone,
                'email' => $doctor->email,
                'Consultation Fee'=>'',
                'specialty' => $doctor->specialities->pluck('name')->implode(', '),
                'active' => $doctor->active ?? true,
                'image' => $doctor->avatar_url,
            ];
        });
    }
    public function addNew($data)
    {
      return  DB::transaction(function () use ($data) {

            $doctor = Doctor::create([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
            ]);
            $doctor->specialities()->sync([$data['speciality_id']]);
            if (!empty($data['image'])) {
                $doctor->addMedia($data['image'])
                    ->toMediaCollection('avatar');
            }

            return $doctor;
        });
    }
}
