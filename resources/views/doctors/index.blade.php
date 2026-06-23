@extends('layouts-main.dashboard')

@section('title', 'الأطباء')

@section('content')
    <div x-data="{
        search: '',
        specialty: '',
        status: '',
        showEditModal: false,
        imagePreview: null,
        doctors: @js($doctors),
        specialities: @js($specialities),
        form: {
            'id': null,
            'image': null,
            'name': null,
            'phone': null,
            'email': null,
            speciality_id: null,
        },
        openEdite(item) {
            this.form = {
                'id': item.id,
                'image': item.image,
                'name': item.name,
                'phone': item.phone,
                'email': item.email,
                speciality_id: item.speciality?.id ?? null,
            };
            console.log(this.form);
            this.showEditModal = true;
        },
    
        get filteredDoctors() {
            const q = this.search.toLowerCase();
    
            return this.doctors.filter(d => {
                const matchSearch =
                    d.name.toLowerCase().includes(q) ||
                    d.specialty.name.toLowerCase().includes(q);
    
                const matchSpecialty =
                    this.specialty.name === '' ||
                    d.specialty.name === this.specialty.name;
    
                const matchStatus =
                    this.status === '' ||
                    (this.status === 'active' && d.active) ||
                    (this.status === 'inactive' && !d.active);
    
                return matchSearch && matchSpecialty && matchStatus;
            });
        },
        deleteDoctor(id) {
            fetch(`/doctors/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector(`input[name='_token']`).value,
                        'Accept': 'application/json'
                    }
                })
                .then(async (res) => {
                    const data = await res.json().catch(() => ({}));
    
                    if (!res.ok || !data.success) {
                        throw new Error(data.message || 'Delete failed');
                    }
    
                    return data;
                })
                .then(() => {
                    // remove from UI
                    this.doctors = this.doctors.filter(d => d.id !== id);
                })
                .catch((err) => {
                    console.error(err);
                    alert('حصل خطأ أثناء الحذف');
                });
        }
    }">
        <x-doctors.stats />

        <div class="flex justify-between ">

            <x-doctors.filters />
            <x-doctors.add-doctor-button />

        </div>
        <x-doctors.grid-view />
        <x-doctors.empty-state />
    </div>
    </div>
@endsection
