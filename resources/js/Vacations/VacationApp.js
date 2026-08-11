import State from "./State";
import Helpers from "./Helpers";
import Getters from "./Getters";
import Actions from "./Actions";
import GlobalHelpers from "../Global/Helpers";

export function vacationApp(serverData = {}) {
    const Vacation = {
        ...State(serverData),
        ...Helpers,
        ...Actions,
        ...GlobalHelpers,
        ...Api,
    };
     Object.defineProperties(
        Vacation,
        Object.getOwnPropertyDescriptors(Getters),
    );

    return Vacation;
}
