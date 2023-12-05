<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage, Link } from "@inertiajs/vue3";

defineProps({ articles: Object });
console.log(usePage().props.articles);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Articles
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-for="article in articles.data" class="max-w-lg mx-auto">
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
                <div class="flex mx-40 rounded-lg mb-10">
                    <div v-for="link in articles.links" class="mx-auto inline">
                        <Link
                            :href="link.url"
                            v-html="link.label"
                            v-if="link.url"
                            class="rounded bg-transparent px-3 py-1.5 text-sm text-neutral-600 duration-300 hover:bg-neutral-100 dark:text-black dark:hover:bg-neutral-700 dark:hover:text-white"
                            :class="{
                                'text-gray-500': !link.url,
                                'bg-neutral-700 text-white': link.active,
                            }"
                        >
                        </Link>
                        <span v-else="link.label"></span>
                    </div>
                    <Link
                        href="/articles"
                        as="button"
                        type="button"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >Back</Link
                    >
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
