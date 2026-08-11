import State from "./State";
import Actions from "./Actions";
import Getters from "./Getters";
import Helpers from "./Helpers";
import Filters from "./Filters";
import GlobalHelpers from "../Global/Helpers";
import GlobalState from '../Global/State';

export function ComplaintApp(serverData) {
    const Complaints = {
        ...State(serverData),
        ...Actions,
        ...Helpers,
        ...Filters,
        ...GlobalHelpers,
        ...GlobalState,
    };
    Object.defineProperties(
        Complaints,
        Object.getOwnPropertyDescriptors(Getters),
    );

    return Complaints;
}
