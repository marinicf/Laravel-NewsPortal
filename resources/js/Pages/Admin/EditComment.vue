<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, usePage, useForm } from "@inertiajs/vue3";

defineProps({
    selectedComment: {
        type: Object,
    },
});

const comment = usePage().props.selectedComment;
const commentForm = useForm({
    id: comment.id,
    comment: comment.comment_text,
});
</script>

<template>
    <Head title="UserInfo" />

    <AuthenticatedLayout>
        <div
            class="flex mx-auto items-center justify center shadow-lg mx-5 my-2"
        >
            <form
                @submit.prevent="
                    commentForm.patch(
                        route('adminComments.update', commentForm.id)
                    )
                "
                class="w-full px-3 bg-white rounded-lg"
            >
                <div class="flex flex-wrap -mx-3 mb-2">
                    <h2 class="px-4 pt-3 text-gray-800 text-lg">
                        Edit comment
                    </h2>
                </div>
                <div class="w-full md:w-full px-3">
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
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
