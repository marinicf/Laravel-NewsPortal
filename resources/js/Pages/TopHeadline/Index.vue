<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, useForm } from "@inertiajs/vue3";

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
});

const capitalizeFirstLetter = (string) => {
    return string.charAt(0).toUpperCase() + string.slice(1);
};
const form = useForm({
    category: "",
    language: "",
});
const submit = () => {
    form.get(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
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
                <form action="topHeadlines" method="GET">
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
                                    v-model="form.category"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                >
                                    <option
                                        v-for="category in allCategories"
                                        :value="category"
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
                                    >
                                        {{ country }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button
                            class="hover:shadow-form rounded-md bg-[#6A64F1] py-3 px-8 text-center text-base font-semibold text-white outline-none"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Get Articles
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div v-if="articles" class="py-12">
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
        <div v-else>
            <p>No articles found.</p>
        </div>
    </AuthenticatedLayout>
</template>
