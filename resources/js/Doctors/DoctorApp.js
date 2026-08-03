import Getters from "./Getters";
import Helpers from "./Helpers";
import State from "./State";
import Actions from "./Actions";
import Api from "./Api";
import GlobalHelpers from '../Global/Helpers';
import GlobalState from '../Global/State';
export function DoctorApp(serverData) {
    const Doctors = {
        ...Helpers,
        ...State(serverData),
        ...Actions,
        ...Api,
        ...GlobalHelpers,
        ...GlobalState,
    };
    Object.defineProperties(
        Doctors,
        Object.getOwnPropertyDescriptors(Getters),
    );

    return Doctors;
}
