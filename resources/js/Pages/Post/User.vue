<script setup lang="ts">
import { Link, useForm, router} from '@inertiajs/vue3';
import { defineProps } from 'vue';
import { ref } from 'vue';

interface Comment {
    id: number;
    content: string;
    created_at: string;
    post_id: number;
}
// Определяем props
const props = defineProps<{
    posts: Array<{
        id: number;
        title: string;
        description: string;
    }>
}>();

const deletePost = (postId: number) => {
    if (confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
        router.delete(`/posts/${postId}`, {
            preserveScroll: true,
        });
    }
};

</script>

<template>
    <div class="min-h-screen bg-gray-900 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Заголовок и кнопки -->
            <div class="flex flex-wrap gap-3">
                <Link
                    href="/posts"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition-colors flex items-center"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Go to all posts
                </Link>
            </div>
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-white">Posts</h1>
                <Link
                    href="/posts/create"
                    class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-md transition-colors"
                >
                    Create Post
                </Link>
            </div>

            <!-- Список постов -->
            <div class="space-y-6">
                <div
                    v-for="post in posts"
                    :key="post.id"
                    class="bg-gray-800 rounded-lg p-6 shadow-lg"
                >
                    <!-- Заголовок поста -->
                    <h2 class="text-xl font-semibold text-white mb-2">{{ post.title }}</h2>

                    <!-- Текст поста -->
                    <p class="text-gray-300 mb-4">{{ post.description }}</p>

                    <Link

                        :href="'/posts/' + post.id + '/edit'"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm transition-colors flex items-center"
                    >
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </Link>

                    <button
                        @click="deletePost(post.id)"
                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm transition-colors flex items-center"
                    >
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                    </button>

                </div>
            </div>


                <!-- Сообщение если постов нет -->
                <div v-if="!posts.length" class="text-center py-12">
                    <p class="text-gray-400 text-lg">No posts yet.</p>
                    <Link
                        href="/posts/create"
                        class="inline-block mt-4 bg-indigo-500 hover:bg-indigo-600 text-white px-6 py-3 rounded-md transition-colors"
                    >
                        Create First Post
                    </Link>
                </div>
            </div>
        </div>


</template>

<style scoped>

</style>

