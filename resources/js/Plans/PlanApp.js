import Actions from "./Actions";
import State from "./State";
export function PlanApp(serverData) {
    const Plans={
        ...State(serverData),
        ...Actions,
    };
    return Plans;
}