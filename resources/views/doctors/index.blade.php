@extends('layouts-main.dashboard')

@section('title', 'الأطباء')

@section('content')
    <div x-data="{
        search: '',
        specialty: '',
        status: '',
        showEditModal: false,
        editDoctor: null,
        imagePreview: null,
        doctors: @js($doctors),
    
        get filteredDoctors() {
            const q = this.search.toLowerCase();
    
            return this.doctors.filter(d => {
                const matchSearch =
                    d.name.toLowerCase().includes(q) ||
                    d.specialty.toLowerCase().includes(q);
    
                const matchSpecialty =
                    this.specialty === '' ||
                    d.specialty === this.specialty;
    
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
