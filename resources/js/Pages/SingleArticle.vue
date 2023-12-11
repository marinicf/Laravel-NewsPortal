<script setup>
import { router, useForm, usePage, Link } from "@inertiajs/vue3";

defineProps({
    article: {
        type: Object,
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
const allFavourites = JSON.parse(JSON.stringify(usePage().props.allFavourites));
const isFavorite = (articleSelect) => {
    //Check if the article is in allFavourites array
    return allFavourites.some((favArticle) => favArticle.url === articleSelect);
};

const addToFavourites = (articleSelect) => {
    router.post(route("favourites.store", articleSelect), {
        preserveScroll: true,
    });
};
const deleteComment = (commentId) => {
    if (confirm("Are you sure?")) {
        router.delete(route("comments.destroy", commentId), {
            preserveScroll: true,
        });
    }
};
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
</script>

<template>
    <section>
        <div class="max-w-lg mx-auto">
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
                    <div class="flex-initial w-64">
                        <button
                            @click="addToFavourites(article)"
                            type="submit"
                            class="inline-flex items-center px-4 py-2 bg-green-500 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-90 transition ease-in-out duration-150"
                            :class="{
                                'bg-yellow-400': isFavorite(article.url),
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
                <div class="" v-if="comment.url == article.url">
                    <div
                        class="bg-gray-100 rounded-md px-5 shadow-lg hover:shadow-2xl transition duration-500 border"
                    >
                        <div class="mt-4">
                            <p class="mt-2 text-md text-gray-600">
                                {{ comment.comment_text }}
                            </p>
                            <div class="flex justify-between items-center">
                                <div
                                    class="mt-2 flex items-center space-x-4 py-3"
                                >
                                    <div class="text-sm font-semibold">
                                        {{ getUserName(comment.user_id) }}
                                        <span class="font-normal">
                                            {{ comment.created_at }}</span
                                        >
                                    </div>
                                </div>
                                <div
                                    v-if="
                                        comment.user_id == user.id ||
                                        user.is_admin
                                    "
                                >
                                    <Link
                                        type="button"
                                        class="mr-2 border bg-yellow-300 px-2 py-1 rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-yellow-200"
                                        :href="
                                            route('comments.edit', comment.id)
                                        "
                                    >
                                        edit
                                    </Link>
                                    <button
                                        class="px-2 py-1 bg-red-500 text-white rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-red-400"
                                        @click="deleteComment(comment.id)"
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
                                :disabled="commentForm.processing"
                            >
                                Post
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>
