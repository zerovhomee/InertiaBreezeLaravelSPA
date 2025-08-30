<script setup lang="ts">
import { defineProps } from 'vue';
import { Link, router, useForm} from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

const isOpen = ref(false);

// Закрытие при клике вне dropdown
const handleClickOutside = (event: MouseEvent) => {
    const dropdown = document.querySelector('.relative');
    if (dropdown && !dropdown.contains(event.target as Node)) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});

const logout = () => {
    router.post('/logout');
    isOpen.value = false;
};

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
        user_id: number;
    }>
}>();

const commentForms = ref<{ [key: number]: string }>({});

props.posts.forEach(post => {
    commentForms.value[post.id] = '';
});
const submitComment = (postId: number) => {
    const content = commentForms.value[postId]?.trim();

    if (!content) return;

    useForm({
        content: content,
        post_id: postId,
    }).post(`/posts/${postId}/comments`, {
        preserveScroll: true,
        onSuccess: () => {
            // Очищаем поле после успешной отправки
            commentForms.value[postId] = '';
        },
    });
};

</script>

<template>
    <div class="min-h-screen bg-gray-900 py-8">
        <!-- Кнопка пользователя в правом верхнем углу -->
        <div class="fixed top-4 right-4 z-50">
            <div class="relative">
                <button
                    @click="isOpen = !isOpen"
                    class="flex items-center space-x-2 bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded-full shadow-lg transition-colors"
                >
                    <span class="hidden sm:block">{{ $page.props.auth.user?.name || 'User' }}</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </button>

                <div
                    v-if="isOpen"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                >
                    <div class="px-4 py-2 border-b border-gray-100">
                        <p class="text-sm font-medium text-gray-900">{{ $page.props.auth.user?.name }}</p>
                        <p class="text-sm text-gray-500">{{ $page.props.auth.user?.email }}</p>
                    </div>

                    <Link href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Profile
                    </Link>

                    <Link href="/posts/my-posts" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        My Posts
                    </Link>

                    <button
                        @click="logout"
                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100"
                    >
                        Sign Out
                    </button>
                </div>
            </div>
        </div>
    <div class="min-h-screen bg-gray-900 py-8">
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

                    <h3 class="text-xl font-semibold text-white mb-2">Author_id: {{ post.user_id }}</h3>

                    <!-- Текст поста -->
                    <p class="text-gray-300 mb-4">{{ post.description }}</p>

                    <!-- Комментарии
                    <div class="border-t border-gray-700 pt-4">
                        <h3 class="text-lg font-medium text-white mb-4">Comments</h3>


                        <div v-if="post.comments && post.comments.length" class="space-y-3 mb-4">
                            <div
                                v-for="comment in post.comments"
                                :key="comment.id"
                                class="bg-gray-700 rounded-lg p-3"
                            >
                                <p class="text-gray-200">{{ comment.content }}</p>

                            </div>
                        </div>

                        <div v-else class="text-gray-400 mb-4">
                            No comments yet. Be the first to comment!
                        </div>


                        <form @submit.prevent="submitComment(post.id)" class="space-y-3">
              <textarea
                  v-model="commentForms[post.id]"
                  placeholder="Write a comment..."
                  rows="3"
                  class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
              ></textarea>

                            <button
                                type="submit"
                                :disabled="!commentForms[post.id]?.trim()"
                                class="bg-indigo-500 hover:bg-indigo-600 disabled:bg-gray-600 disabled:cursor-not-allowed text-white px-4 py-2 rounded-md transition-colors"
                            >
                                Add Comment
                            </button>
                        </form>
                    </div>
                </div>
                -->
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
    </div>


</template>

<style scoped>

</style>
