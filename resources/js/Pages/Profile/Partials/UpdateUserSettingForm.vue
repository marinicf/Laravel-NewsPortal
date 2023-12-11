<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";

defineProps({
    allLanguages: {
        type: Array,
    },
    allCategories: {
        type: Array,
    },
    allCountries: {
        type: Array,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    category: user.category,
    country: user.country,
    language: user.language,
});
const capitalizeFirstLetter = (string) => {
    return string.charAt(0).toUpperCase() + string.slice(1);
};

const languageCodes = [
    { ar: "Arabic" },
    { en: "English" },
    { cn: "Chinese" },
    { de: "German" },
    { es: "Spanish" },
    { fr: "French" },
    { he: "Hebrew" },
    { it: "Italian" },
    { nl: "Dutch" },
    { no: "Norwegian" },
    { pt: "Portuguese" },
    { ru: "Russian" },
    { sv: "Swedish" },
    { ud: "Undefined" },
];

const transformedLanguage = (code) => {
    let language = "";
    [...languageCodes].forEach((x) => {
        if (Object.keys(x) == code) {
            language = x[Object.keys(x)];
        }
    });
    return language;
};

const countryCodes = [
    { ae: "United Arab Emirates" },
    { ar: "Argentina" },
    { at: "Austria" },
    { au: "Australia" },
    { be: "Belgium" },
    { bg: "Bulgaria" },
    { br: "Brazil" },
    { ca: "Canada" },
    { ch: "Switzerland" },
    { cn: "China" },
    { co: "Colombia" },
    { cu: "Cuba" },
    { cz: "Czech Republic" },
    { de: "Germany" },
    { eg: "Egypt" },
    { fr: "France" },
    { gb: "United Kingdom" },
    { gr: "Greece" },
    { hk: "Hong Kong" },
    { hu: "Hungary" },
    { id: "Indonesia" },
    { ie: "Ireland" },
    { il: "Israel" },
    { in: "India" },
    { it: "Italy" },
    { jp: "Japan" },
    { kr: "South Korea" },
    { lt: "Lithuania" },
    { lv: "Latvia" },
    { ma: "Morocco" },
    { mx: "Mexico" },
    { my: "Malaysia" },
    { ng: "Nigeria" },
    { nl: "Netherlands" },
    { no: "Norway" },
    { nz: "New Zealand" },
    { ph: "Philippines" },
    { pl: "Poland" },
    { pt: "Portugal" },
    { ro: "Romania" },
    { rs: "Serbia" },
    { ru: "Russia" },
    { sa: "Saudi Arabia" },
    { se: "Sweden" },
    { sg: "Singapore" },
    { si: "Slovenia" },
    { sk: "Slovakia" },
    { th: "Thailand" },
    { tr: "Turkey" },
    { tw: "Taiwan" },
    { ua: "Ukraine" },
    { us: "United States" },
    { ve: "Venezuela" },
    { za: "South Africa" },
];
const transformedCountry = (code) => {
    let country = "";
    [...countryCodes].forEach((x) => {
        if (Object.keys(x)[0] === code) {
            country = x[Object.keys(x)[0]];
        }
    });
    return country;
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                News Settings Information
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                Update your account's category, country and language.
            </p>
        </header>

        <form
            @submit.prevent="form.patch(route('userSettings.update'))"
            class="mt-6 space-y-6"
        >
            <div>
                <InputLabel for="category" value="Category" />

                <select
                    id="category"
                    class="mt-1 block w-full"
                    v-model="form.category"
                >
                    <option
                        v-for="category in allCategories"
                        :value="category"
                        :selected="form.category === category"
                    >
                        {{ capitalizeFirstLetter(category) }}
                    </option>
                </select>
            </div>

            <div>
                <InputLabel for="country" value="Country" />

                <select
                    id="country"
                    class="mt-1 block w-full"
                    v-model="form.country"
                >
                    <option
                        v-for="country in allCountries"
                        :value="country"
                        :selected="form.country === country"
                    >
                        {{ transformedCountry(country) }}
                    </option>
                </select>
            </div>

            <div>
                <InputLabel for="language" value="Language" />

                <select
                    id="language"
                    class="mt-1 block w-full"
                    v-model="form.language"
                >
                    <option
                        v-for="language in allLanguages"
                        :value="language"
                        :selected="form.language === language"
                    >
                        {{ transformedLanguage(language) }}
                    </option>
                </select>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
