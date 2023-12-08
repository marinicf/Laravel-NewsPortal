<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import SingleArticle from "../SingleArticle.vue";

defineProps({
    articles: {
        type: Array,
    },
    allCategories: {
        type: Array,
    },
    allCountries: {
        type: Array,
    },
    allComments: {
        type: Array,
    },
});
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

const capitalizeFirstLetter = (string) => {
    return string.charAt(0).toUpperCase() + string.slice(1);
};

const user = usePage().props.auth.user;
let selectedCategory = usePage().props.currentCategory;
let selectedCountry = usePage().props.currentCountry;

const form = useForm({
    category: selectedCategory ? selectedCategory : user.category,
    country: selectedCountry ? selectedCountry : user.category,
});
</script>

<template>
    <Head title="TopHeadlines" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Articles
            </h2>
        </template>
        <div class="flex items-center justify-center p-12">
            <div class="mx-auto w-full max-w-[550px]">
                <form action="topHeadlines" method="get">
                    <div class="-mx-3 flex flex-wrap">
                        <div class="w-full px-3 sm:w-1/2">
                            <div class="mb-5">
                                <label
                                    for="category"
                                    class="mb-3 block text-base font-medium text-[#07074D]"
                                >
                                    Select category
                                </label>
                                <select
                                    id="category"
                                    name="category"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500"
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
                        </div>
                        <div class="w-full px-3 sm:w-1/2">
                            <div class="mb-5">
                                <label
                                    for="country"
                                    class="mb-3 block text-base font-medium text-[#07074D]"
                                >
                                    Select country:
                                </label>
                                <select
                                    id="country"
                                    name="country"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500"
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
                        </div>
                        <div class="w-full px-3 sm:w-1/2">
                            <button
                                type="submit"
                                class="hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-8 text-center text-base font-semibold text-white outline-none"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                Get Articles
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div v-if="articles" class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <SingleArticle
                    v-for="article in articles"
                    :article="article"
                    :all-comments="allComments"
                ></SingleArticle>
            </div>
        </div>
        <div v-else>
            <p>No articles found.</p>
        </div>
    </AuthenticatedLayout>
</template>
