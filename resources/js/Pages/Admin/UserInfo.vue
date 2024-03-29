<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import { toRefs } from "vue";
defineProps({
    user: {
        type: Object,
    },
    userFavourites: {
        type: Array,
    },
    userComments: {
        type: Array,
    },
    allCategories: {
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
const deleteComment = (comment_id) => {
    if (confirm("Are you sure?")) {
        router.delete(route("comments.destroy", comment_id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="UserInfo" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ user ? user.name : "" }}
            </h2>
        </template>
        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Link
                    :href="route('admin.index')"
                    as="button"
                    type="button"
                    class="inline-flex items-center px-4 py-2 mb-5 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >Back</Link
                >
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-t-lg">
                    <div class="p-4 text-gray-900">User Favourites</div>
                </div>
                <div class="">
                    <div
                        v-if="userFavourites"
                        class="relative overflow-x-auto shadow-md sm:rounded-b-lg"
                    >
                        <table
                            class="w-full text-sm text-left rtl:text-right text-gray-500"
                        >
                            <thead
                                class="text-xs text-gray-900 uppercase bg-gray-400"
                            >
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Article Title
                                    </th>
                                    <th scope="col" class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="fav in userFavourites"
                                    class="odd:bg-white even:bg-gray-50 border-b"
                                >
                                    <td class="px-6 py-4">{{ fav.title }}</td>
                                    <td class="px-6 py-4">
                                        <button
                                            @click="deleteFavArticle(fav.id)"
                                            class="px-2 py-1 bg-red-600 text-white rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-red-500"
                                        >
                                            delete
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else>
                        <p>This user dose not have favourites.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-t-lg">
                    <div class="p-4 text-gray-900">User Comments</div>
                </div>
                <div class="">
                    <div
                        v-if="userComments"
                        class="relative overflow-x-auto shadow-md sm:rounded-b-lg"
                    >
                        <table
                            class="w-full text-sm text-left rtl:text-right text-gray-500"
                        >
                            <thead
                                class="text-xs text-gray-900 uppercase bg-gray-400"
                            >
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Comment text
                                    </th>
                                    <th scope="col" class="px-6 py-3"></th>
                                    <th scope="col" class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="comment in userComments"
                                    class="odd:bg-white even:bg-gray-50 border-b"
                                >
                                    <td class="px-6 py-4">
                                        {{ comment.comment_text }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <Link
                                            :href="
                                                route(
                                                    'adminComments.edit',
                                                    comment.id
                                                )
                                            "
                                            class="px-2 py-1 bg-yellow-400 text-white rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-yellow-300"
                                        >
                                            Edit
                                        </Link>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button
                                            @click="deleteComment(comment.id)"
                                            class="px-2 py-1 bg-red-600 text-white rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-red-500"
                                        >
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else>
                        <p>This user dose not have favourites.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
