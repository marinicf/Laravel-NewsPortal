<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import SingleArticle from "../SingleArticle.vue";
import { Head, router, useForm, usePage } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { onMounted } from "vue";

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
    allFavourites: {
        type: Array,
    },
    allComments: {
        type: Array,
    },
});
const user = usePage().props.auth.user;
const allUserNames = usePage().props.allUsersNames;
const getUserName = (userId) => {
    let name = "";
    allUserNames.forEach((user) => {
        if (user.id == userId) {
            name = user.name;
        }
    });
    return name;
};
const form = useForm({
    sortBy: "publishedAt",
    language: user.language,
    from: null,
    to: null,
});
const commentForm = useForm({
    comment: null,
    url: null,
    userId: user.id,
});
const formatDate = (date) => {};
const submitComment = (articleUrl) => {
    // Set the article.url in the commentForm
    commentForm.url = articleUrl;

    // Now, you can submit the form using your existing logic
    commentForm.post(route("comments.store"), {
        preserveScroll: true,
    });

    // If you want to clear the commentForm.url after submission
    commentForm.comment = null;
};

const capitalizeFirstLetter = (string) => {
    return string.charAt(0).toUpperCase() + string.slice(1);
};
const allFavourites = JSON.parse(JSON.stringify(usePage().props.allFavourites));
let processing = false;
const isFavorite = (article) => {
    //Check if the article is in allFavourites array
    return allFavourites.some((favArticle) => favArticle.url === article);
};

const addToFavourites = (article) => {
    router.post(route("favourites.store", article), { preserveScroll: true });
};
const deleteComment = (commentId) => {
    if (confirm("Are you sure?")) {
        router.delete(route("comments.destroy", commentId), {
            preserveScroll: true,
        });
    }
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
        <div class="p-12 ml-60">
            <form @submit.prevent="form.get(route('articles.paginated'))">
                <div class="flex flex-row">
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
                                        class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
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
                                        class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                                    />
                                </div>
                            </div>
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

        <div v-if="articles" class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- <SingleArticle
                    v-for="article in articles"
                    :article="article"
                ></SingleArticle> -->
                <section>
                    <div class="max-w-lg mx-auto" v-for="article in articles">
                        <div
                            class="bg-white shadow-md border border-gray-200 rounded-t-lg max-w-lg"
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
                                        type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-green-500 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-90 transition ease-in-out duration-150"
                                        :class="{
                                            'bg-yellow-500': isFavorite(
                                                article.url
                                            ),
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
                        <div v-for="comment in allComments" :key="comment.id">
                            <div
                                class="px-10"
                                v-if="comment.url == article.url"
                            >
                                <div
                                    class="bg-white rounded-md px-5 py-4 shadow-lg hover:shadow-2xl transition duration-500 border"
                                >
                                    <div class="mt-4">
                                        <p class="mt-2 text-md text-gray-600">
                                            {{ comment.comment_text }}
                                        </p>
                                        <div
                                            class="flex justify-between items-center"
                                        >
                                            <div
                                                class="mt-2 flex items-center space-x-4 py-3"
                                            >
                                                <div
                                                    class="text-sm font-semibold"
                                                >
                                                    {{
                                                        getUserName(
                                                            comment.user_id
                                                        )
                                                    }}
                                                    <span class="font-normal">
                                                        {{
                                                            comment.created_at
                                                        }}</span
                                                    >
                                                </div>
                                            </div>
                                            <div>
                                                <button
                                                    v-if="
                                                        comment.user_id ==
                                                            user.id ||
                                                        user.is_admin
                                                    "
                                                    class="px-2 py-1 bg-red-600 text-white rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-red-500"
                                                    @click="
                                                        deleteComment(
                                                            comment.id
                                                        )
                                                    "
                                                >
                                                    del
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="flex mx-auto items-center justify center shadow-lg mb-5 rounded-b-lg"
                        >
                            <form
                                @submit.prevent="submitComment(article.url)"
                                class="w-full px-3 bg-white"
                            >
                                <div class="flex flex-wrap -mx-3 mb-2">
                                    <h2 class="px-4 pt-3 text-gray-800 text-lg">
                                        Add a new comment
                                    </h2>
                                </div>
                                <div class="w-full md:w-full px-3">
                                    <input
                                        type="hidden"
                                        name="url"
                                        v-model="commentForm.url"
                                    />
                                    <textarea
                                        v-model="commentForm.comment"
                                        class="bg-gray-100 rounded border border-gray-400 leading-normal resize-none w-full h-15 px-3 font-medium placeholder-gray-700 focus:outline-none focus:bg-white"
                                        name="comment"
                                        placeholder="Type Your Comment"
                                        required
                                    ></textarea>
                                </div>
                                <div class="md:w-full flex md:w-full p-2">
                                    <div class="-mr-2">
                                        <button
                                            type="submit"
                                            class="border px-5 py-2 rounded-md shadow"
                                            :disabled="form.processing"
                                        >
                                            Post
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
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
