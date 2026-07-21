import actions from "./actions";
import api from "./api";
import constants from "./constants";
import getters from "./getters";
import state from "./state";
import globalHelpers from "../global/helpers";
import helpers from "./helpers";
export function clinicsApp(serverData) {
    const clinics = {
        ...state(serverData),
        ...actions,
        ...api,
        ...helpers,
        ...constants,
        ...globalHelpers,
    };
    Object.defineProperties(clinics, Object.getOwnPropertyDescriptors(getters));

    return clinics;
}
