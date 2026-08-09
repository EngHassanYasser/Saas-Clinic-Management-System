import axios from "axios";

export async function getAvailableSlots(clinidId,doctorId,visiteDate) {
    const { data } = await axios.get(
        `/appointments/AvailableAppointments/clinic/${clinidId}/doctor/${doctorId}/visitDate/${visiteDate}`,
    );
    return data;
}
export async function getDoctorServices(specialityId) {
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
