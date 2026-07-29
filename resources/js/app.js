import Alpine from "alpinejs";
import axios from "axios";
import "./clinic/adsApp";
import "./clinic/bookingApp";
import { bookingForm } from "./appointments/booking/booking";
import { vicationForm } from "./vications/vication";
import { doctorsForm } from "./doctors/doctorsForm";
import { schedulesForm } from "./schedules/schedulesForm";
import { clinicServicesForm } from "./clinicServices/clinicServicesForm";
import { subscriptionsForm } from "./subscriptions/subscriptionsForm";
import { complaintsForm } from "./complaints/complaintsForm";
import { clinicsApp } from "./clinic/clinicsApp";
import { PlansApp } from "./plans/PlansApp";
import { DashboardApp } from "./dashboard/DashboardApp";
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
const token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
}
window.Alpine = Alpine;
Alpine.data("bookingForm", bookingForm);
Alpine.data("vicationForm", vicationForm);
Alpine.data("doctorsForm", doctorsForm);
Alpine.data("schedulesForm", schedulesForm);
Alpine.data("clinicServicesForm", clinicServicesForm);
Alpine.data("subscriptionsForm", subscriptionsForm);
Alpine.data("complaintsForm", complaintsForm);
Alpine.data("clinicsApp", clinicsApp);
Alpine.data("PlansApp", PlansApp);
Alpine.data("DashboardApp", DashboardApp);
Alpine.start();
