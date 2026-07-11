import Alpine from "alpinejs";
import { bookingForm } from "./appointments/booking/booking";
import { vicationForm } from "./vications/vication";
import { doctorsForm } from "./doctors/doctorsForm";
import { schedulesForm } from "./schedules/schedulesForm";
import {clinicServicesForm} from "./clinicServices/clinicServicesForm";
import {subscriptionsForm} from './subscriptions/subscriptionsForm';
window.Alpine = Alpine;

import "./alpine/clinic/clinicsApp";
import "./alpine/clinic/adsApp";
import "./alpine/clinic/bookingApp";
Alpine.data("bookingForm", bookingForm);
Alpine.data("vicationForm",vicationForm);
Alpine.data("doctorsForm",doctorsForm);
Alpine.data("schedulesForm",schedulesForm);
Alpine.data("clinicServicesForm",clinicServicesForm);
Alpine.data("subscriptionsForm",subscriptionsForm);
Alpine.start();
