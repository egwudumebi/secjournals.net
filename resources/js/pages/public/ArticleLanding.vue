<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import {
    ArrowDownTrayIcon,
    ArrowLeftIcon,
    BookOpenIcon,
    CalendarDaysIcon,
} from '@heroicons/vue/24/outline';
import { publicApi } from '../../api/client';
import { useSiteBranding } from '../../composables/useSiteBranding';
import { applySeo } from '../../utils/seo';
import JournalTitle from '../../components/JournalTitle.vue';

const route = useRoute();
const { branding, loadBranding } = useSiteBranding();
const article = ref(null);
const loading = ref(true);
const error = ref('');

const journalFullName = computed(
    () => branding.value?.journal_full_name
        || branding.value?.site_tagline
        || 'Software Engineering Consortium Journals',
);

const keywordList = computed(() => {
    if (!article.value?.keywords) {
        return [];
    }

    return String(article.value.keywords)
        .split(',')
        .map((item) => item.trim())
        .filter(Boolean);
});

onMounted(async () => {
    await loadBranding();
    await load();
});

watch(article, (value) => {
    if (!value) {
        return;
    }

    const siteName = branding.value?.site_name || 'SEC Journals';
    applySeo({
        title: `${value.title} | ${siteName}`,
        description: value.abstract?.slice(0, 160) || value.title,
        type: 'article',
        keywords: value.keywords || undefined,
        url: window.location.origin + value.landing_url,
    });
});

async function load() {
    loading.value = true;
    error.value = '';
    try {
        const { data } = await publicApi().get(`/articles/${route.params.slug}`);
        article.value = data.data;
    } catch {
        article.value = null;
        error.value = 'Article not found.';
    } finally {
        loading.value = false;
    }
}

function formatDate(value) {
    if (!value) {
        return null;
    }

    return new Date(`${value}T00:00:00`).toLocaleDateString('en-GB', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    });
}

function orcidUrl(orcid) {
    if (!orcid) {
        return null;
    }

    const id = String(orcid).replace(/^https?:\/\/orcid\.org\//, '');

    return `https://orcid.org/${id}`;
}
</script>

<template>
    <div class="bg-surface-muted pb-20 lg:pb-0">
        <section class="border-b border-border-cool bg-white">
            <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">
                <RouterLink
                    to="/articles"
                    class="inline-flex items-center gap-2 text-sm font-medium text-accent-gold hover:underline"
                >
                    <ArrowLeftIcon class="size-4 shrink-0" />
                    All articles
                </RouterLink>

                <div v-if="loading" class="mt-8 text-sm text-slate-500">Loading article…</div>
                <div
                    v-else-if="error"
                    class="mt-8 rounded-2xl border border-border-cool bg-white p-6 text-center text-sm text-slate-500 sm:p-8"
                >
                    {{ error }}
                </div>

                <article v-else-if="article" class="mt-5 sm:mt-6">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-accent-gold">
                        {{ branding?.site_name || 'SEC Journals' }}
                    </p>
                    <p class="mt-1.5 text-sm font-medium leading-snug text-slate-600">
                        <JournalTitle :text="article.journal_title || journalFullName" />
                    </p>

                    <h1 class="mt-3 font-display text-2xl font-bold tracking-tight text-slate-900 sm:mt-4 sm:text-4xl">
                        {{ article.title }}
                    </h1>

                    <div class="mt-4 space-y-3 sm:mt-5">
                        <div
                            v-for="(author, index) in article.authors"
                            :key="`${author.name}-${index}`"
                            class="rounded-xl border border-border-cool bg-surface-muted/60 px-3 py-2.5 sm:border-0 sm:bg-transparent sm:px-0 sm:py-0 sm:border-l-2 sm:border-l-accent-gold sm:pl-3 sm:rounded-none"
                        >
                            <p class="text-sm font-semibold text-slate-900 sm:text-base">{{ author.name }}</p>
                            <p v-if="author.affiliation" class="mt-0.5 text-xs leading-relaxed text-slate-600 sm:text-sm">
                                {{ author.affiliation }}
                            </p>
                            <a
                                v-if="author.orcid"
                                :href="orcidUrl(author.orcid)"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="mt-1 inline-block text-xs text-accent-gold hover:underline sm:text-sm"
                            >
                                ORCID: {{ author.orcid }}
                            </a>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-1.5 sm:mt-6 sm:gap-2">
                        <span class="rounded-full bg-accent-gold/10 px-2.5 py-1 text-xs font-medium text-accent-gold">
                            {{ article.article_type }}
                        </span>
                        <span
                            v-if="article.volume_issue_label"
                            class="rounded-full bg-slate-100 px-2.5 py-1 text-xs text-slate-700"
                        >
                            {{ article.volume_issue_label }}
                        </span>
                        <span
                            v-if="article.pages"
                            class="rounded-full bg-slate-100 px-2.5 py-1 text-xs text-slate-700"
                        >
                            pp. {{ article.pages }}
                        </span>
                        <span
                            v-if="article.is_crossref_sample"
                            class="rounded-full bg-amber-50 px-2.5 py-1 text-xs text-amber-800"
                        >
                            Crossref sample
                        </span>
                    </div>

                    <!-- Mobile-first PDF CTA (desktop keeps sidebar) -->
                    <div v-if="article.pdf_url" class="mt-5 lg:hidden">
                        <a
                            :href="article.pdf_url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn-gold inline-flex w-full items-center justify-center gap-2 !rounded-xl !py-3.5"
                        >
                            <ArrowDownTrayIcon class="size-4" />
                            Full-text PDF
                        </a>
                    </div>
                </article>
            </div>
        </section>

        <section v-if="article && !loading && !error" class="mx-auto max-w-4xl px-4 py-6 sm:px-6 sm:py-10 lg:px-8">
            <div class="grid gap-4 sm:gap-6 lg:grid-cols-[1fr_240px]">
                <div class="order-2 space-y-4 sm:space-y-6 lg:order-1">
                    <div class="rounded-2xl border border-border-cool bg-white p-4 sm:p-8">
                        <h2 class="font-display text-lg font-bold text-slate-900 sm:text-xl">Abstract</h2>
                        <p class="mt-3 text-sm leading-relaxed text-slate-700 whitespace-pre-wrap sm:text-base">
                            {{ article.abstract }}
                        </p>

                        <div v-if="keywordList.length" class="mt-5 sm:mt-6">
                            <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-500">Keywords</h3>
                            <div class="mt-2 flex flex-wrap gap-1.5 sm:gap-2">
                                <span
                                    v-for="keyword in keywordList"
                                    :key="keyword"
                                    class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs text-slate-700 sm:text-sm"
                                >
                                    {{ keyword }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-border-cool bg-white p-4 sm:p-8">
                        <h2 class="font-display text-lg font-bold text-slate-900 sm:text-xl">How to cite</h2>
                        <p class="mt-3 break-words text-sm leading-relaxed text-slate-700">{{ article.citation }}</p>
                        <p v-if="!article.doi" class="mt-3 text-xs leading-relaxed text-slate-500">
                            DOI will be registered with Crossref after membership approval. This page is the permanent
                            landing URL for that future DOI.
                        </p>
                        <p v-else class="mt-3 break-all text-sm">
                            <a
                                :href="`https://doi.org/${article.doi}`"
                                class="text-accent-gold hover:underline"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                https://doi.org/{{ article.doi }}
                            </a>
                        </p>
                    </div>

                    <div class="rounded-2xl border border-border-cool bg-white p-4 sm:p-8">
                        <h2 class="font-display text-lg font-bold text-slate-900 sm:text-xl">Copyright &amp; licensing</h2>
                        <dl class="mt-4 space-y-3 text-sm">
                            <div>
                                <dt class="font-semibold text-slate-900">Copyright</dt>
                                <dd class="mt-1 text-slate-700">
                                    {{ article.copyright || '© SEC Journals / Software Engineering Consortium.' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-slate-900">License</dt>
                                <dd class="mt-1 text-slate-700">{{ article.license || 'All rights reserved.' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <aside class="order-1 space-y-3 sm:space-y-4 lg:order-2 lg:sticky lg:top-24 lg:self-start">
                    <div class="hidden rounded-2xl border border-border-cool bg-white p-5 lg:block">
                        <a
                            v-if="article.pdf_url"
                            :href="article.pdf_url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn-gold inline-flex w-full items-center justify-center gap-2 !rounded-xl"
                        >
                            <ArrowDownTrayIcon class="size-4" />
                            Full-text PDF
                        </a>
                        <p v-else class="text-sm text-slate-500">PDF forthcoming.</p>
                        <p class="mt-3 text-xs leading-relaxed text-slate-500">
                            The DOI resolves to this landing page. Readers access the PDF from here.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-border-cool bg-white p-4 sm:p-5">
                        <h3 class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                            <CalendarDaysIcon class="size-4 shrink-0 text-accent-gold" />
                            Publication timeline
                        </h3>
                        <dl class="mt-3 grid grid-cols-2 gap-3 text-sm sm:mt-4 sm:grid-cols-1 sm:space-y-3 sm:gap-0">
                            <div v-if="article.received_at">
                                <dt class="text-xs text-slate-500 sm:text-sm">Received</dt>
                                <dd class="font-medium text-slate-900">{{ formatDate(article.received_at) }}</dd>
                            </div>
                            <div v-if="article.revised_at">
                                <dt class="text-xs text-slate-500 sm:text-sm">Revised</dt>
                                <dd class="font-medium text-slate-900">{{ formatDate(article.revised_at) }}</dd>
                            </div>
                            <div v-if="article.accepted_at">
                                <dt class="text-xs text-slate-500 sm:text-sm">Accepted</dt>
                                <dd class="font-medium text-slate-900">{{ formatDate(article.accepted_at) }}</dd>
                            </div>
                            <div v-if="article.published_at">
                                <dt class="text-xs text-slate-500 sm:text-sm">Published</dt>
                                <dd class="font-medium text-slate-900">{{ formatDate(article.published_at) }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="rounded-2xl border border-border-cool bg-white p-4 sm:p-5">
                        <h3 class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                            <BookOpenIcon class="size-4 shrink-0 text-accent-gold" />
                            Browse
                        </h3>
                        <div class="mt-3 grid grid-cols-1 gap-2 text-sm sm:flex sm:flex-col">
                            <RouterLink to="/articles/current-issue" class="text-accent-gold hover:underline">
                                Current issue
                            </RouterLink>
                            <RouterLink to="/articles" class="text-accent-gold hover:underline">
                                All articles
                            </RouterLink>
                            <RouterLink to="/journals" class="text-accent-gold hover:underline">
                                Journals
                            </RouterLink>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        <!-- Sticky mobile PDF bar -->
        <div
            v-if="article?.pdf_url && !loading && !error"
            class="fixed inset-x-0 bottom-0 z-40 border-t border-border-cool bg-white/95 p-3 backdrop-blur lg:hidden"
        >
            <a
                :href="article.pdf_url"
                target="_blank"
                rel="noopener noreferrer"
                class="btn-gold inline-flex w-full items-center justify-center gap-2 !rounded-xl !py-3.5"
            >
                <ArrowDownTrayIcon class="size-4" />
                Full-text PDF
            </a>
        </div>
    </div>
</template>
