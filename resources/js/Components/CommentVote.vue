<template>
    <div class="bg-gray-700 rounded-lg p-3 mb-3">
        <div class="flex justify-between items-start mb-2">
            <div class="flex-1">
                <p class="font-medium text-white text-sm">{{ comment.user?.name }}</p>
                <p class="text-gray-200 mt-1">{{ comment.content }}</p>
            </div>
            <span class="text-xs text-gray-400">{{ formatDate(comment.created_at) }}</span>
        </div>

        <!-- Блок голосования -->
        <div class="flex items-center space-x-2 mt-2">
            <!-- Кнопка лайка -->
            <button
                @click="vote(comment.id, 1)"
                :class="[
          'p-1 rounded transition-colors',
          comment.user_vote === 1
            ? 'text-green-500 bg-green-500/20'
            : 'text-gray-400 hover:text-green-500'
        ]"
                :disabled="!$page.props.auth.user"
                title="Upvote"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905a3.61 3.61 0 01-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                </svg>
            </button>

            <!-- Рейтинг -->
            <span class="text-sm font-medium text-white min-w-[20px] text-center">
        {{ comment.rating || 0 }}
      </span>

            <!-- Кнопка дизлайка -->
            <button
                @click="vote(comment.id, -1)"
                :class="[
          'p-1 rounded transition-colors',
          comment.user_vote === -1
            ? 'text-red-500 bg-red-500/20'
            : 'text-gray-400 hover:text-red-500'
        ]"
                :disabled="!$page.props.auth.user"
                title="Downvote"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018c.163 0 .326.02.485.06L17 4m0 0v9m0-9h2.765a2 2 0 011.789 2.894l-3.5 7A2 2 0 0118.264 15H17m0 0h-2" />
                </svg>
            </button>

        </div>
    </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

interface User {
    id: number;
    name: string;
}

interface Comment {
    id: number;
    content: string;
    user_id: number;
    post_id: number;
    created_at: string;
    user?: User;
    rating?: number;
    user_vote?: number | null;
}

const props = defineProps<{
    comment: Comment;
}>();

const page = usePage();

import axios from 'axios';

const vote = async (commentId: number, voteValue: number) => {
    if (!page.props.auth.user) {
        alert('Please login to vote');
        return;
    }

    try {
        const response = await axios.post(`/comments/${commentId}/vote`, {
            vote: voteValue
        }, {
            headers: {
                'X-CSRF-TOKEN': page.props.csrf_token
            }
        });

        const data = response.data;

    } catch (error) {
        console.error('Error voting:', error);
    }
};

const formatDate = (dateString: string): string => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>
