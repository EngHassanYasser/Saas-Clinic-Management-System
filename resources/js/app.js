import Alpine from "alpinejs";
import { bookingForm } from "./appointments/booking/booking";
import { vicationForm } from "./vications/vication";
window.Alpine = Alpine;

import "./alpine/clinic/clinicsApp";
import "./alpine/clinic/adsApp";
import "./alpine/clinic/bookingApp";
Alpine.data("bookingForm", bookingForm);
Alpine.data("vicationForm",vicationForm);
Alpine.start();
