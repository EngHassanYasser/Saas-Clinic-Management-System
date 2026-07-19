import axios from 'axios';

export async function getAvailableSlots(currentAppointment) {
    const { data } = await axios.get(
        `/appointments/${currentAppointment.id}/${currentAppointment.visit_date}/availableSlots`
    );
    return data;
}