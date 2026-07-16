const statusMap = {
    pending: { label: "في الانتظار", cls: "bg-blue-100 text-blue-700" },
    under_review: { label: "قيد المراجعة", cls: "bg-amber-100 text-amber-700" },
    resolved: { label: "تم الحل", cls: "bg-emerald-100 text-emerald-700" },
    rejected: { label: "تم الرفض", cls: "bg-red-100 text-emerald-700" },
};
const severities = [
    { label: "منخفضة", value: "low" },
    { label: "متوسطة", value: "medium" },
    { label: "عالية", value: "high" },
    { label: "حرجة", value: "urgent" },
];
const issue_types = [
    { label: "اختر نوع المشكلة", value: "" },
    { label: "طبية", value: "medical" },
    { label: "خدمة", value: "service" },
    { label: "وقت انتظار", value: "waiting_time" },
    { label: "تعامل الموظفين", value: "staff_behavior" },
    { label: "فواتير", value: "billing" },
    { label: "أخرى", value: "other" },
];
const departments = [
    { label: "الأشعة", value: "radiology" },
    { label: "الاستقبال", value: "reception" },
    { label: "المعامل", value: "laboratory" },
    { label: "الصيدلية", value: "pharmacy" },
    { label: "الحسابات", value: "accounting" },
    { label: "خدمة العملاء", value: "customer_service" },
    { label: "التمريض", value: "nursing" },
    { label: "الإدارة", value: "administration" },
    { label: "العيادات", value: "clinics" },
    { label: "الدعم الفني", value: "technical_support" },
];
const statuses = [
    {
        value: "pending",
        label: "في الانتظار",
        border: "border-blue-200",
        text: "text-blue-600",
        checked: "peer-checked:bg-blue-600 peer-checked:border-blue-600",
    },
    {
        value: "under_review",
        label: "قيد المراجعة",
        border: "border-amber-200",
        text: "text-amber-600",
        checked: "peer-checked:bg-amber-500 peer-checked:border-amber-500",
    },
    {
        value: "resolved",
        label: "تم الحل",
        border: "border-emerald-200",
        text: "text-emerald-600",
        checked: "peer-checked:bg-emerald-600 peer-checked:border-emerald-600",
    },
    {
        value: "rejected",
        label: "تم الرفض",
        border: "border-red-200",
        text: "text-red-600",
        checked: "peer-checked:bg-red-600 peer-checked:border-red-600",
    },
];

const priorityMap = {
    urgent: { label: "عاجل", cls: "bg-red-100 text-red-600" },
    normal: { label: "عادي", cls: "bg-gray-100 text-gray-500" },
};

export default {
    statusMap,
    statuses,
    priorityMap,
    departments,
    issue_types,
    severities,
};
