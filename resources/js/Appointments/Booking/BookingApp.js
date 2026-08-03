import Data from "./Data";
import Actions from "./Actions";
import Getters from "./Getters";
import Slots from "./Slots";
import Helpers from "./Helpers";
import State from "./State";
import Constants from "./Constants";
import GlobalHelpers from '../../Global/Helpers';

export function BookingApp(serverData) {
    const Booking = {
        ...State(serverData),
        ...Actions,
        ...Slots,
        ...Helpers,
        ...Data,
        ...Constants,
        ...GlobalHelpers,
    };
    Object.defineProperties(Booking, Object.getOwnPropertyDescriptors(Getters));

    return Booking;
}
