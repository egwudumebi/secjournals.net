<script setup>
import { onMounted, ref } from 'vue';
import { RouterLink } from 'vue-router';
import { ArrowRightIcon, BookOpenIcon, MagnifyingGlassIcon } from '@heroicons/vue/24/outline';
import { publicApi } from '../../api/client';
import { useSiteBranding } from '../../composables/useSiteBranding';
import JournalTitle from '../../components/JournalTitle.vue';

const { branding, loadBranding } = useSiteBranding();
const articles = ref([]);
const meta = ref({ current_page: 1, last_page: 1, total: 0 });
const loading = ref(true);
const search = ref('');
const page = ref(1);

onMounted(async () => {
    await loadBranding();
    await load();
});

async function load() {
    loading.value = true;
    try {
        const { data } = await publicApi().get('/articles', {
            params: {
                search: search.value || undefined,
                page: page.value,
                per_page: 12,
            },
        });
        articles.value = data.data || [];
        meta.value = data.meta || meta.value;
    } finally {
        loading.value = false;
    }
}

async function submitSearch() {
    page.value = 1;
    await load();
}

async function goPage(next) {
    page.value = next;
    await load();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>

<template>
    <div class="bg-surface-muted">
        <section class="border-b border-border-cool bg-white">
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
                <p class="label-caps text-accent-gold">Published research</p>
                <h1 class="mt-2 font-display text-2xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                    Articles
                </h1>
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-text-secondary sm:text-base">
                    Browse peer-reviewed articles in
                    <JournalTitle :text="branding?.journal_full_name || 'Software Engineering Consortium Journals'" />.
                    Each article has a permanent public landing page suitable for Crossref DOI resolution.
                </p>
                <div class="mt-5 grid grid-cols-1 gap-2 sm:flex sm:flex-wrap sm:gap-3">
                    <RouterLink to="/articles/current-issue" class="btn-gold !w-full justify-center !rounded-xl sm:!w-auto">
                        Current issue
                    </RouterLink>
                    <RouterLink to="/journals" class="btn-secondary !w-full justify-center !rounded-xl sm:!w-auto">
                        Journals
                    </RouterLink>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-10 lg:px-8">
            <form class="mb-6 flex flex-col gap-2 sm:mb-8 sm:flex-row sm:gap-3" @submit.prevent="submitSearch">
                <div class="relative flex-1">
                    <MagnifyingGlassIcon class="pointer-events-none absolute left-3 top-1/2 size-5 -translate-y-1/2 text-slate-400" />
                    <input
                        v-model="search"
                        type="search"
                        class="input !rounded-xl !py-3 !pl-10"
                        placeholder="Search title, keywords, or abstract"
                    />
                </div>
                <button type="submit" class="btn-primary !w-full !rounded-xl !py-3 sm:!w-auto sm:!px-6">
                    Search
                </button>
            </form>

            <div v-if="loading" class="rounded-2xl border border-border-cool bg-white px-4 py-10 text-center text-sm text-slate-500">
                Loading articles…
            </div>

            <div v-else-if="!articles.length" class="rounded-2xl border border-dashed border-border-cool bg-white p-8 text-center sm:p-10">
                <BookOpenIcon class="mx-auto size-8 text-slate-300" />
                <p class="mt-3 font-medium text-slate-700">No published articles found.</p>
            </div>

            <ul v-else class="space-y-3 sm:space-y-4">
                <li
                    v-for="article in articles"
                    :key="article.uuid"
                    class="overflow-hidden rounded-2xl border border-border-cool bg-white shadow-sm transition hover:border-accent-gold/30 hover:shadow-md"
                >
                    <div class="flex flex-col p-4 sm:p-6">
                        <div class="flex flex-wrap gap-1.5">
                            <span class="rounded-full bg-accent-gold/10 px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-wide text-accent-gold">
                                {{ article.article_type }}
                            </span>
                            <span
                                v-if="article.volume_issue_label"
                                class="rounded-full bg-slate-100 px-2.5 py-0.5 text-[11px] font-medium text-slate-600"
                            >
                                {{ article.volume_issue_label }}
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

                        <p class="mt-2 text-sm leading-relaxed text-slate-600">
                            {{ article.author_names }}
                        </p>

                        <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-slate-500 sm:line-clamp-2">
                            {{ article.abstract }}
                        </p>

                        <RouterLink
                            :to="article.landing_url"
                            class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-institutional px-4 py-3 text-sm font-semibold text-white transition hover:bg-black sm:mt-5 sm:w-auto sm:self-start sm:bg-transparent sm:px-0 sm:py-0 sm:text-accent-gold sm:hover:bg-transparent sm:hover:underline"
                        >
                            View article
                            <ArrowRightIcon class="size-4" />
                        </RouterLink>
                    </div>
                </li>
            </ul>

            <div
                v-if="meta.last_page > 1"
                class="mt-6 grid grid-cols-2 gap-2 sm:mt-8 sm:flex sm:items-center sm:justify-between sm:gap-3"
            >
                <button
                    type="button"
                    class="btn-secondary !rounded-xl !py-3"
                    :disabled="meta.current_page <= 1"
                    @click="goPage(meta.current_page - 1)"
                >
                    Previous
                </button>
                <button
                    type="button"
                    class="btn-secondary !rounded-xl !py-3 sm:order-3"
                    :disabled="meta.current_page >= meta.last_page"
                    @click="goPage(meta.current_page + 1)"
                >
                    Next
                </button>
                <p class="col-span-2 text-center text-xs text-slate-500 sm:order-2 sm:col-span-1 sm:text-sm">
                    Page {{ meta.current_page }} of {{ meta.last_page }} · {{ meta.total }} articles
                </p>
            </div>
        </section>
    </div>
</template>
