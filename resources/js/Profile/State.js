export default function (serverData = {}) {
    return {
        cities: serverData.cities ?? [],
        user:serverData.user ?? null,
        selected: serverData.user.city ?? null,
        show: false,
        preview: null,
        open: false,
    };
}
