<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
defineProps({
    user: {
        type: Object,
    },
    userFavourites: {
        type: Array,
    },
});
</script>

<template>
    <Head title="UserInfo" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ user.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 text-gray-900">User favourites</div>
                </div>
                <div class="">
                    <div
                        v-if="userFavourites"
                        class="relative overflow-x-auto shadow-md sm:rounded-lg"
                    >
                        <ul>
                            <li
                                v-for="fav in userFavourites"
                                class="p-2 border"
                            >
                                {{ fav.title }}
                            </li>
                        </ul>
                    </div>
                    <div v-else>
                        <p>This user dose not have favourites.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-10"
                >
                    <div class="p-4 text-gray-900">User Comments</div>
                </div>
                <div>
                    <div
                        class="relative overflow-x-auto shadow-md sm:rounded-lg"
                    ></div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
