export default function(serverData={}) {
    return {
        workDays:[],
        isActive: true,
        imagePreview: null,
        slots: [],
         search: '',
        specialty: '',
        status: '',
        showEditModal: false,
        imagePreview: null,
        doctors: serverData.doctors ?? [],
        specialities:serverData.specialities ?? [],
        form: {
            'id': null,
            'image': null,
            'name': null,
            'phone': null,
            'email': null,
            speciality_id: null,
        },
        
    }
}