<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import SingleArticle from "../SingleArticle.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";

defineProps({
    articles: {
        type: Array,
    },
    allSortByOptions: {
        type: Array,
    },
    allLanguages: {
        type: Array,
    },
    allComments: {
        type: Array,
    },
});
const user = usePage().props.auth.user;

const form = useForm({
    keyword: "",
    sortBy: "publishedAt",
    language: user.language,
    from: null,
    to: null,
});

const capitalizeFirstLetter = (string) => {
    return string.charAt(0).toUpperCase() + string.slice(1);
};
</script>
<template>
    <Head title="Articles" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Articles
            </h2>
        </template>
        <div class="p-7 ml-52">
            <form @submit.prevent="form.get(route('articles.paginated'))">
                <div class="flex flex-row wrap">
                    <div class="px-3 basis-1/5 md:basis-1/6">
                        <div class="mb-5">
                            <label
                                for="keyword"
                                class="mb-3 block text-base font-medium text-[#07074D]"
                                >Search</label
                            >
                            <input
                                v-model="form.keyword"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                type="text"
                                id="keyword"
                            />
                        </div>
                    </div>
                    <div class="px-3 basis-1/5 md:basis-1/6">
                        <div class="mb-5">
                            <label
                                for="language"
                                class="mb-3 block text-base font-medium text-[#07074D]"
                                >Select language</label
                            >
                            <select
                                id="language"
                                v-model="form.language"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            >
                                <option
                                    v-for="language in allLanguages"
                                    :value="language"
                                    :selected="form.language === language"
                                >
                                    {{ language }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="px-3 basis-1/5 md:basis-1/6">
                        <div class="w-full flex">
                            <div class="w-full px-3 sm:w-1/2">
                                <div class="mb-5">
                                    <label
                                        for="from"
                                        class="mb-3 block text-base font-medium text-[#07074D]"
                                        >From</label
                                    >
                                    <input
                                        type="date"
                                        v-model="form.from"
                                        id="from"
                                        class="w-full rounded-md border border-[#e0e0e0] bg-white py-2 px-4 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                                    />
                                </div>
                            </div>
                            <div class="w-full px-3 sm:w-1/2">
                                <div class="mb-5">
                                    <label
                                        for="to"
                                        class="mb-3 block text-base font-medium text-[#07074D]"
                                        >To</label
                                    >
                                    <input
                                        type="date"
                                        v-model="form.to"
                                        id="to"
                                        class="w-full rounded-md border border-[#e0e0e0] bg-white py-2 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-3 basis-1/5 md:basis-1/6">
                        <div class="mb-5">
                            <label
                                for="sortBy"
                                class="mb-3 block text-base font-medium text-[#07074D]"
                                >Sort by</label
                            >
                            <select
                                id="sortBy"
                                v-model="form.sortBy"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            >
                                <option
                                    v-for="sortBy in allSortByOptions"
                                    :value="sortBy"
                                    :selected="form.sortBy === sortBy"
                                >
                                    {{ capitalizeFirstLetter(sortBy) }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="pb-5 basis-1/5 md:basis-1/6 flex items-end">
                        <button
                            class="hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-8 text-center text-base font-semibold text-white outline-none"
                            type="submit"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Get articles
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div v-if="articles" class="py-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <SingleArticle
                    v-for="article in articles"
                    :article="article"
                    :all-comments="allComments"
                ></SingleArticle>
            </div>
        </div>
        <div v-else class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-10">
            <p
                class="border border-red-200 p-5 rounded-md shadow-md bg-red-100 text-center"
            >
                No articles found.
            </p>
        </div>
    </AuthenticatedLayout>
</template>
