<script setup>
import { usePage, router } from "@inertiajs/vue3";
import Checkbox from "@/Components/Checkbox.vue";

defineProps({
    article: {
        type: Object,
    },
    allFavourites: {
        type: Array,
    },
});

const allFavourites = JSON.parse(JSON.stringify(usePage().props.allFavourites));
let processing = false;
const isFavorite = (article) => {
    //Check if the article is in allFavourites array
    return allFavourites.some((favArticle) => favArticle.url === article);
};

const addToFavourites = (article) => {
    router.post(route("favourites.store", article, { preserveScroll: true }));
};
const addComment = () => {};
</script>

<template>
    <section>
        <div class="max-w-lg mx-auto">
            <div
                class="bg-white shadow-md border border-gray-200 rounded-lg max-w-lg mb-5"
            >
                <a v-bind:href="article.url">
                    <img
                        class="rounded-t-lg"
                        :src="article.urlToImage"
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
                <div class="flex mb-3">
                    <a
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 text-center items-center flex-initial w-32 mx-14"
                        :href="article.url"
                    >
                        Read more
                    </a>
                    <div class="flex-initial w-65">
                        <button
                            @click="addToFavourites(article)"
                            class="inline-flex items-center px-4 py-2 bg-green-500 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-90 transition ease-in-out duration-150"
                            :class="{
                                'bg-yellow-500': isFavorite(article.url),
                            }"
                            :disabled="isFavorite(article.url)"
                        >
                            {{
                                isFavorite(article.url)
                                    ? "Favourite"
                                    : "Add to Favourites"
                            }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
