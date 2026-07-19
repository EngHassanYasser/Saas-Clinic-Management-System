import data from "./data";
import actions from "./actions";
import getters from "./getters";
import slotMethods from "./slots";
import helpers from "./helpers";
import state from "./state";
import constants from "./constants";
import globalHelpers from '../../global/helpers';

export function bookingForm(serverData) {
    const booking = {
        ...state(serverData),
        ...actions,
        ...slotMethods,
        ...helpers,
        ...data,
        ...constants,
        ...globalHelpers,
    };
    Object.defineProperties(booking, Object.getOwnPropertyDescriptors(getters));

    return booking;
}
