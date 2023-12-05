<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";

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

const deleteFavArticle = (article_id) => {
    if (confirm("Are you sure?")) {
        router.delete(route("favourites.destroy", article_id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Favourites" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Favourite Articles
            </h2>
        </template>

        <div v-if="articles" class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-for="article in articles" class="max-w-lg mx-auto">
                    <div
                        class="bg-white shadow-md border border-gray-200 rounded-lg max-w-lg mb-5"
                    >
                        <a v-bind:href="article.url">
                            <img
                                class="rounded-t-lg"
                                v-bind:src="article.image_url"
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
                        </div>
                        <div class="flex justify-evenly mb-3">
                            <a
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 text-center inline-flex items-center"
                                :href="article.url"
                            >
                                Read more
                            </a>
                            <div>
                                <button
                                    @click="deleteFavArticle(article.id)"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
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
