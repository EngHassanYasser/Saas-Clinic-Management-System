import axios from "axios";

export async function getAvailableSlots(currentAppointment) {
    console.log(currentAppointment.doctor.id);
    const { data } = await axios.get(
        //16,
        `appointments/AvailableAppointments/clinic/${currentAppointment.clinic.id}/doctor/${currentAppointment.doctor.id}/visitDate/${currentAppointment.visit_date}`,
    );
    return data;
}
export async function getClinicServices(specialityId) {
    try {
        const response = await axios.get(
            `/clinic/services/speciality/${specialityId}`,
        );

        return response.data;
    } catch (error) {
        console.error(error);

        return {
            data: [],
        };
    }
}
export async function getAvailableClinics(specialityId, serviceId) {
    try {
        const { data } = await axios.get(
            `/clinics/speciality/${specialityId}/service/${serviceId}`,
        );

        return data.data;
    } catch (error) {
        console.error(error);

        return [];
    }
}
export async function getAvailableDoctors(clinicId, specialityId, serviceId) {
    try {
        const { data } = await axios.get(
            `/doctors/clinic/${clinicId}/speciality/${specialityId}/service/${serviceId}`,
        );

        return data.data;
    } catch (error) {
        console.error(error);

        return [];
    }
}
