<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";

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
});
const form = useForm({
    sortBy: "",
    language: "en",
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
        <div class="flex items-center justify-center p-12">
            <div class="mx-auto w-full max-w-[550px]">
                <form @submit.prevent="form.get(route('articles.paginated'))">
                    <div class="-mx-3 flex flex-wrap">
                        <div class="w-full px-3 sm:w-1/2">
                            <div class="mb-5">
                                <label
                                    for="sortBy"
                                    class="mb-3 block text-base font-medium text-[#07074D]"
                                >
                                    Sort by:
                                </label>
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
                        <div class="w-full px-3 sm:w-1/2">
                            <div class="mb-5">
                                <label
                                    for="language"
                                    class="mb-3 block text-base font-medium text-[#07074D]"
                                >
                                    Select language:
                                </label>
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
                    </div>
                    <div>
                        <button
                            class="hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-8 text-center text-base font-semibold text-white outline-none"
                            type="submit"
                        >
                            Get articles
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-for="article in articles" class="max-w-lg mx-auto">
                    <div
                        class="bg-white shadow-md border border-gray-200 rounded-lg max-w-lg mb-5"
                    >
                        <a v-bind:href="article.url">
                            <img
                                class="rounded-t-lg"
                                v-bind:src="article.urlToImage"
                                alt=""
                            />
                        </a>
                        <div class="p-5">
                            <a v-bind:href="article.url">
                                <h5
                                    class="text-gray-900 font-bold text-2xl tracking-tight mb-2"
                                >
                                    {{ article.title }}
                                </h5>
                            </a>
                            <p class="font-normal text-gray-700 mb-3">
                                {{ article.description }}
                            </p>
                            <a
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 text-center inline-flex items-center"
                                v-bind:href="article.url"
                            >
                                Read more
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
