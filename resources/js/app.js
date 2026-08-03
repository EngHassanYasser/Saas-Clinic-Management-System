import Alpine from "alpinejs";
import axios from "axios";
import "./Clinic/AdApp";
import "./Clinic/BookingApp1";
import { BookingApp } from "./Appointments/Booking/BookingApp";
import { vicationApp } from "./Vications/vicationApp";
import { DoctorApp } from "./Doctors/DoctorApp";
import { ScheduleApp } from "./Schedules/ScheduleApp";
import { ClinicServiceApp } from "./ClinicServices/ClinicServiceApp";
import { SubscriptionApp } from "./Subscriptions/SubscriptionApp";
import { ComplaintApp } from "./Complaints/ComplaintApp";
import {ClinicApp} from "./Clinic/ClinicApp";
import { PlanApp } from "./Plans/PlanApp";
import { DashboardApp } from "./Dashboard/DashboardApp";
import { ProfileApp } from "./Profile/ProfileApp";
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
const token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
}
window.Alpine = Alpine;
Alpine.data("BookingApp", BookingApp);
Alpine.data("VicationApp", vicationApp);
Alpine.data("DoctorApp", DoctorApp);
Alpine.data("ScheduleApp", ScheduleApp);
Alpine.data("ClinicServiceApp", ClinicServiceApp);
Alpine.data("SubscriptionApp", SubscriptionApp);
Alpine.data("ComplaintApp", ComplaintApp);
Alpine.data("ClinicApp", ClinicApp);
Alpine.data("PlanApp", PlanApp);
Alpine.data("DashboardApp", DashboardApp);
Alpine.data("ProfileApp", ProfileApp);
Alpine.start();
