import state from "./state";
import actions from "./actions";
import helpers from "./helpers";
import getters from "./getters";
import api from "./api";
import globalHelpers from '../global/helpers';

export function clinicServicesForm(serverData) {
    const clinicServices = {
        ...state(serverData),
        ...actions,
        ...helpers,
        ...api,
        ...globalHelpers,
    };
    Object.defineProperties(
        clinicServicesForm,
        Object.getOwnPropertyDescriptors(getters),
    );
    return clinicServices;
}
