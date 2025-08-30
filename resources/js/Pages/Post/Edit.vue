<template>
    <div class="min-h-screen bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto">
                <!-- Заголовок и кнопка назад -->
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-2xl font-bold text-white">Edit Post</h1>
                    <Link
                        href="/my-posts"
                        class="text-gray-400 hover:text-white transition-colors"
                    >
                        ← Back to My Posts
                    </Link>
                </div>

                <form @submit.prevent="submit">
                    <div class="space-y-12">
                        <div class="border-b border-white/10 pb-12">
                            <h2 class="text-base font-semibold text-white">Edit Post</h2>
                            <p class="mt-1 text-sm text-gray-400">Update your post information below.</p>

                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <div class="sm:col-span-4">
                                    <label for="title" class="block text-sm font-medium text-white">Title</label>
                                    <div class="mt-2">
                                        <div class="flex items-center rounded-md bg-white/5 pl-3 outline-1 -outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
                                            <input
                                                v-model="form.title"
                                                type="text"
                                                id="title"
                                                placeholder="Enter post title"
                                                class="block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-white placeholder:text-gray-500 focus:outline-none sm:text-sm"
                                            />
                                        </div>
                                        <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">
                                            {{ form.errors.title }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-span-full">
                                    <label for="description" class="block text-sm font-medium text-white">Content</label>
                                    <div class="mt-2">
                    <textarea
                        v-model="form.description"
                        id="description"
                        rows="6"
                        placeholder="Write your post content..."
                        class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm"
                    ></textarea>
                                        <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">
                                            {{ form.errors.description }}
                                        </div>
                                    </div>
                                    <p class="mt-3 text-sm text-gray-400">Write your post content here</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-x-4">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded-md bg-indigo-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="form.processing">Updating...</span>
                            <span v-else>Update Post</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';

interface Post {
    id: number;
    title: string;
    description: string;
}

const props = defineProps<{
    post: Post;
}>();

const form = useForm({
    title: props.post.title,
    description: props.post.description,
});

const submit = () => {
    form.patch(`/posts/${props.post.id}`);
};
</script>

<style scoped>
/* Дополнительные стили если нужно */
</style>
