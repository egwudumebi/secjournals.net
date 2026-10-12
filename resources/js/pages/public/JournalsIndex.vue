<script setup>
import { onMounted, ref } from 'vue';
import { RouterLink } from 'vue-router';
import { ArrowRightIcon, BookOpenIcon } from '@heroicons/vue/24/outline';
import { publicApi } from '../../api/client';
import { useSiteBranding } from '../../composables/useSiteBranding';

const { branding, loadBranding } = useSiteBranding();
const journals = ref([]);
const loading = ref(true);

onMounted(async () => {
    await loadBranding();
    try {
        const { data } = await publicApi().get('/journals');
        journals.value = data.data || [];
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <div>
        <section class="border-b border-slate-200 bg-gradient-to-br from-surface-muted via-white to-institutional/5">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <p class="label-caps text-institutional">{{ branding?.parent_org?.short_name || 'SEC' }} Journals</p>
                <h1 class="mt-2 font-display text-4xl font-bold tracking-tight text-institutional-dark">
                    Our Journals
                </h1>
                <p class="mt-3 max-w-3xl text-base text-text-secondary">
                    The Software Engineering Consortium publishes peer-reviewed journals spanning software,
                    electronic and electrical engineering, communication and media engineering, artificial
                    intelligence and prompt engineering, and management and business engineering.
                </p>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div v-if="loading" class="text-slate-500">Loading journals…</div>
            <div v-else class="grid gap-5 md:grid-cols-2">
                <RouterLink
                    v-for="journal in journals"
                    :key="journal.uuid"
                    :to="journal.landing_url"
                    class="group rounded-2xl border border-slate-200 bg-white p-6 transition hover:border-institutional/40 hover:shadow-md"
                >
                    <div class="flex items-start gap-4">
                        <span class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-institutional/10 text-institutional">
                            <BookOpenIcon class="size-6" />
                        </span>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold uppercase tracking-wide text-institutional">{{ journal.short_name }}</p>
                            <h2 class="mt-1 font-display text-xl font-bold text-slate-900 group-hover:text-institutional">
                                {{ journal.name }}
                            </h2>
                            <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ journal.description }}</p>
                            <span class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-institutional">
                                Open journal
                                <ArrowRightIcon class="size-4" />
                            </span>
                        </div>
                    </div>
                </RouterLink>
            </div>
        </section>
    </div>
</template>
