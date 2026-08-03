import State from "./State";
import Helpers from "./Helpers";
import Getters from "./Getters";
import Actions from "./Actions";
import GlobalHelpers from "../Global/Helpers";
import Api from "./Api";

export function vicationApp(serverData = {}) {
    const Vication = {
        ...State(serverData),
        ...Helpers,
        ...Actions,
        ...GlobalHelpers,
        ...Api,
    };
     Object.defineProperties(
        Vication,
        Object.getOwnPropertyDescriptors(Getters),
    );

    return Vication;
}
