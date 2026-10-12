<script setup>
import { onMounted, ref, watch } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import { publicApi } from '../../api/client';

const route = useRoute();
const journal = ref(null);
const articles = ref([]);
const loading = ref(true);

async function load() {
    loading.value = true;
    try {
        const { data } = await publicApi().get(`/journals/${route.params.slug}/articles`, { params: { per_page: 50 } });
        journal.value = data.journal || null;
        articles.value = data.data || [];
    } finally {
        loading.value = false;
    }
}

onMounted(load);
watch(() => route.params.slug, load);
</script>

<template>
    <div class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        <RouterLink v-if="journal" :to="journal.landing_url" class="text-sm font-medium text-institutional hover:underline">
            ← {{ journal.name }}
        </RouterLink>
        <h1 class="mt-4 font-display text-3xl font-bold text-slate-900">Articles</h1>
        <p v-if="journal" class="mt-2 text-slate-600">{{ journal.name }}</p>

        <div v-if="loading" class="mt-8 text-slate-500">Loading…</div>
        <div v-else-if="!articles.length" class="mt-8 rounded-2xl border border-dashed border-slate-200 p-8 text-slate-500">
            No published articles yet.
        </div>
        <ul v-else class="mt-8 space-y-4">
            <li v-for="article in articles" :key="article.uuid" class="rounded-2xl border border-slate-200 bg-white p-5">
                <RouterLink :to="article.landing_url" class="font-display text-xl font-bold text-slate-900 hover:text-institutional">
                    {{ article.title }}
                </RouterLink>
                <p class="mt-2 text-sm text-slate-600">{{ article.author_names }}</p>
                <p class="mt-3 line-clamp-3 text-sm text-slate-600">{{ article.abstract }}</p>
            </li>
        </ul>
    </div>
</template>
