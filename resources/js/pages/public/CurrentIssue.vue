<script setup>
import { onMounted, ref } from 'vue';
import { RouterLink } from 'vue-router';
import { ArrowRightIcon, BookOpenIcon } from '@heroicons/vue/24/outline';
import { publicApi } from '../../api/client';
import { useSiteBranding } from '../../composables/useSiteBranding';
import JournalTitle from '../../components/JournalTitle.vue';

const { branding, loadBranding } = useSiteBranding();
const issue = ref(null);
const articles = ref([]);
const loading = ref(true);

onMounted(async () => {
    await loadBranding();
    try {
        const { data } = await publicApi().get('/articles/current-issue');
        issue.value = data.data?.issue || null;
        articles.value = data.data?.articles || [];
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <div class="bg-surface-muted">
        <section class="border-b border-border-cool bg-white">
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
                <p class="label-caps text-accent-gold">SEC Journals</p>
                <h1 class="mt-2 font-display text-2xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                    Current Issue
                </h1>
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-text-secondary sm:text-base">
                    Latest published issue of
                    <JournalTitle :text="branding?.journal_full_name || 'Software Engineering Consortium Journals'" />.
                </p>
                <p v-if="issue?.label" class="mt-3 text-base font-semibold text-accent-gold sm:mt-4 sm:text-lg">
                    {{ issue.label }}
                </p>
                <div class="mt-5">
                    <RouterLink
                        to="/articles"
                        class="btn-secondary inline-flex !w-full justify-center !rounded-xl sm:!w-auto"
                    >
                        Browse all articles
                    </RouterLink>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-10 lg:px-8">
            <div v-if="loading" class="rounded-2xl border border-border-cool bg-white px-4 py-10 text-center text-sm text-slate-500">
                Loading current issue…
            </div>

            <div
                v-else-if="!articles.length"
                class="rounded-2xl border border-dashed border-border-cool bg-white p-8 text-center sm:p-10"
            >
                <BookOpenIcon class="mx-auto size-8 text-slate-300" />
                <p class="mt-3 font-medium text-slate-700">No articles in the current issue yet.</p>
            </div>

            <ol v-else class="space-y-3 sm:space-y-4">
                <li
                    v-for="article in articles"
                    :key="article.uuid"
                    class="overflow-hidden rounded-2xl border border-border-cool bg-white p-4 shadow-sm sm:p-6"
                >
                    <div class="flex flex-wrap gap-1.5">
                        <span class="rounded-full bg-accent-gold/10 px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-wide text-accent-gold">
                            {{ article.article_type }}
                        </span>
                        <span
                            v-if="article.pages"
                            class="rounded-full bg-slate-100 px-2.5 py-0.5 text-[11px] font-medium text-slate-600"
                        >
                            pp. {{ article.pages }}
                        </span>
                    </div>
                    <RouterLink
                        :to="article.landing_url"
                        class="mt-3 block font-display text-lg font-bold leading-snug text-slate-900 hover:text-accent-gold sm:text-xl"
                    >
                        {{ article.title }}
                    </RouterLink>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ article.author_names }}</p>
                    <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-slate-500 sm:line-clamp-2">
                        {{ article.abstract }}
                    </p>
                    <RouterLink
                        :to="article.landing_url"
                        class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-institutional px-4 py-3 text-sm font-semibold text-white transition hover:bg-black sm:w-auto sm:self-start sm:bg-transparent sm:px-0 sm:py-0 sm:text-accent-gold sm:hover:bg-transparent sm:hover:underline"
                    >
                        Open landing page
                        <ArrowRightIcon class="size-4" />
                    </RouterLink>
                </li>
            </ol>
        </section>
    </div>
</template>
