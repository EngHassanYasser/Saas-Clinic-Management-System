import actions from "./actions";
import state from "./state";
export function PlansApp(serverData) {
    const plans={
        ...state(serverData),
        ...actions,
    };
    return plans;
}