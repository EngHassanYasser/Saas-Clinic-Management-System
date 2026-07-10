export default {

        steps: ["التخصص", "الخدمه", "العيادة", "الدكتور", "الموعد"],

        clinics: [
            {
                id: 1,
                name: "عيادة النور لطب الأسنان",
                area: "المعادي",
            },
            {
                id: 2,
                name: "مركز سمايل دنتال",
                area: "مدينة نصر",
            },
            {
                id: 3,
                name: "عيادة الجلدية التخصصية",
                area: "الزمالك",
            },
            {
                id: 4,
                name: "مركز العظام والمفاصل",
                area: "مصر الجديدة",
            },
            {
                id: 5,
                name: "عيادة الأطفال الحديثة",
                area: "الدقي",
            },
            {
                id: 6,
                name: "مركز القلب المتخصص",
                area: "المهندسين",
            },
            {
                id: 7,
                name: "عيادة العيون المتقدمة",
                area: "التجمع الخامس",
            },
        ],

        doctors: [
            {
                id: 1,
                clinic_id: 1,
                specialty_id: 1,
                name: "د. أحمد سليم",
                duration: 30,
                rating: 4.8,
            },
            {
                id: 2,
                clinic_id: 2,
                specialty_id: 1,
                name: "د. مريم فتحي",
                duration: 20,
                rating: 4.6,
            },
            {
                id: 3,
                clinic_id: 3,
                specialty_id: 2,
                name: "د. كريم عادل",
                duration: 25,
                rating: 4.9,
            },
            {
                id: 4,
                clinic_id: 4,
                specialty_id: 3,
                name: "د. سارة حسن",
                duration: 40,
                rating: 4.7,
            },
            {
                id: 5,
                clinic_id: 5,
                specialty_id: 4,
                name: "د. نور الدين",
                duration: 20,
                rating: 4.5,
            },
            {
                id: 6,
                clinic_id: 6,
                specialty_id: 5,
                name: "د. هبة منصور",
                duration: 30,
                rating: 4.9,
            },
            {
                id: 7,
                clinic_id: 7,
                specialty_id: 6,
                name: "د. طارق يوسف",
                duration: 25,
                rating: 4.6,
            },
        ],

        arabicDays: ["أحد", "اثنين", "ثلاثاء", "أربعاء", "خميس", "جمعة", "سبت"],

        arabicMonths: [
            "يناير",
            "فبراير",
            "مارس",
            "أبريل",
            "مايو",
            "يونيو",
            "يوليو",
            "أغسطس",
            "سبتمبر",
            "أكتوبر",
            "نوفمبر",
            "ديسمبر",
        ],
};
