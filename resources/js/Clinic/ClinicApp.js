import Actions from "./Actions";
import Api from "./Api";
import Constants from "./Constants";
import Getters from "./Getters";
import State from "./State";
import GlobalHelpers from "../Global/Helpers";
import Helpers from "./Helpers";
export function ClinicApp(serverData) {
    const Clinics = {
        ...State(serverData),
        ...Actions,
        ...Api,
        ...Helpers,
        ...Constants,
        ...GlobalHelpers,
    };
    Object.defineProperties(Clinics, Object.getOwnPropertyDescriptors(Getters));

    return Clinics;
}
