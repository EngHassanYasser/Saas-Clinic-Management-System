import State from "./State";
import Actions from "./Actions";
import Helpers from "./Helpers";
import Getters from "./Getters";
import Api from "./Api";
import GlobalHelpers from '../Global/Helpers';
import GlobalState from '../Global/State';

export function DoctorServiceApp(serverData) {
    const DoctorServices = {
        ...State(serverData),
        ...Actions,
        ...Helpers,
        ...Api,
        ...GlobalHelpers,
        ...GlobalState,
    };
    Object.defineProperties(
        DoctorServices,
        Object.getOwnPropertyDescriptors(Getters),
    );
    return DoctorServices;
}
