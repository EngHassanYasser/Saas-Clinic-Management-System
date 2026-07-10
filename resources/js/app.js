import Alpine from "alpinejs";
import { bookingForm } from "./appointments/booking/booking";
window.Alpine = Alpine;

import "./alpine/clinic/clinicsApp";
import "./alpine/clinic/adsApp";
import "./alpine/clinic/bookingApp";
Alpine.data("bookingForm", bookingForm);
console.log(Alpine);
Alpine.start();
