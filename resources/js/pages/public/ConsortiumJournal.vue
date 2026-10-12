<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import { ArrowRightIcon, UserGroupIcon } from '@heroicons/vue/24/outline';
import { publicApi } from '../../api/client';
import { useSiteBranding } from '../../composables/useSiteBranding';
import { applySeo } from '../../utils/seo';

const route = useRoute();
const { branding, loadBranding } = useSiteBranding();
const journal = ref(null);
const loading = ref(true);
const error = ref('');

const board = computed(() => journal.value?.editorial_board || []);
const articles = computed(() => journal.value?.recent_articles || []);

async function load() {
    loading.value = true;
    error.value = '';
    try {
        const { data } = await publicApi().get(`/journals/${route.params.slug}`);
        journal.value = data.data;
        const siteName = branding.value?.site_name || 'SEC Journals';
        applySeo({
            title: `${journal.value.name} | ${siteName}`,
            description: journal.value.description || journal.value.name,
        });
    } catch {
        journal.value = null;
        error.value = 'Journal not found.';
    } finally {
        loading.value = false;
    }
}

onMounted(async () => {
    await loadBranding();
    await load();
});

watch(() => route.params.slug, load);
</script>

<template>
    <div>
        <section class="border-b border-slate-200 bg-gradient-to-br from-surface-muted via-white to-institutional/5">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <RouterLink to="/journals" class="text-sm font-medium text-institutional hover:underline">← All journals</RouterLink>
                <div v-if="loading" class="mt-8 text-slate-500">Loading…</div>
                <div v-else-if="error" class="mt-8 text-slate-500">{{ error }}</div>
                <template v-else-if="journal">
                    <p class="mt-4 label-caps text-institutional">{{ branding?.parent_org?.short_name || 'SEC' }} · {{ journal.short_name }}</p>
                    <h1 class="mt-2 max-w-4xl font-display text-3xl font-bold tracking-tight text-institutional-dark sm:text-4xl">
                        {{ journal.name }}
                    </h1>
                    <p class="mt-4 max-w-3xl text-base text-text-secondary">{{ journal.description }}</p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <RouterLink :to="journal.editorial_board_url" class="btn-institutional inline-flex items-center gap-2">
                            <UserGroupIcon class="size-4" />
                            Editorial board
                        </RouterLink>
                        <RouterLink :to="journal.articles_url" class="btn-secondary !rounded-xl">Articles</RouterLink>
                        <RouterLink :to="journal.guidelines_url" class="btn-secondary !rounded-xl">Author guidelines</RouterLink>
                        <RouterLink to="/member/journal/submit" class="btn-gold">Submit manuscript</RouterLink>
                    </div>
                </template>
            </div>
        </section>

        <section v-if="journal && !loading" class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr]">
                <div>
                    <div class="flex items-end justify-between gap-4">
                        <h2 class="font-display text-2xl font-bold text-slate-900">Editorial leadership</h2>
                        <RouterLink :to="journal.editorial_board_url" class="text-sm font-semibold text-institutional hover:underline">
                            Full board
                        </RouterLink>
                    </div>
                    <ul class="mt-5 space-y-3">
                        <li
                            v-for="member in board"
                            :key="member.uuid"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-3"
                        >
                            <p class="text-xs font-semibold uppercase tracking-wide text-institutional">{{ member.role_title }}</p>
                            <p class="mt-1 font-semibold text-slate-900">{{ member.name }}</p>
                            <p v-if="member.affiliation" class="text-sm text-slate-600">{{ member.affiliation }}</p>
                        </li>
                    </ul>
                </div>

                <div>
                    <div class="flex items-end justify-between gap-4">
                        <h2 class="font-display text-2xl font-bold text-slate-900">Recent articles</h2>
                        <RouterLink :to="journal.articles_url" class="inline-flex items-center gap-1 text-sm font-semibold text-institutional hover:underline">
                            View all <ArrowRightIcon class="size-4" />
                        </RouterLink>
                    </div>
                    <div v-if="!articles.length" class="mt-5 rounded-xl border border-dashed border-slate-200 p-6 text-sm text-slate-500">
                        No published articles yet for this journal.
                    </div>
                    <ul v-else class="mt-5 space-y-3">
                        <li v-for="article in articles" :key="article.uuid" class="rounded-xl border border-slate-200 bg-white p-4">
                            <RouterLink :to="article.landing_url" class="font-semibold text-slate-900 hover:text-institutional">
                                {{ article.title }}
                            </RouterLink>
                            <p class="mt-1 text-sm text-slate-600">{{ article.author_names }}</p>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
    </div>
</template>
