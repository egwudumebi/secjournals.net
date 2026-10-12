<script setup>
import { computed, onMounted, ref } from 'vue';
import { RouterLink } from 'vue-router';
import { publicApi } from '../../api/client';
import { useSiteBranding } from '../../composables/useSiteBranding';
import { useOrgInfo } from '../../composables/useOrgInfo';

const { branding, loadBranding } = useSiteBranding();
const { contact, loadOrgInfo } = useOrgInfo();
const officers = ref([]);
const journals = ref([]);

const orgName = computed(() => branding.value?.parent_org?.full_name || 'Software Engineering Consortium');
const motto = computed(() => branding.value?.parent_org?.motto || 'Fusion of Academia and Industry to provide resources for productivity and advancement of the society.');

onMounted(async () => {
    await Promise.all([loadBranding(), loadOrgInfo()]);
    const [officersRes, journalsRes] = await Promise.all([
        publicApi().get('/journals/consortium-officers'),
        publicApi().get('/journals'),
    ]);
    officers.value = officersRes.data.data || [];
    journals.value = journalsRes.data.data || [];
});
</script>

<template>
    <div>
        <section class="border-b border-slate-200 bg-gradient-to-br from-surface-muted via-white to-institutional/5">
            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
                <div class="grid items-center gap-10 lg:grid-cols-[1.2fr_0.8fr]">
                    <div>
                        <p class="label-caps text-institutional">Parent organization</p>
                        <h1 class="mt-2 font-display text-4xl font-bold tracking-tight text-institutional-dark sm:text-5xl">
                            {{ branding?.parent_org?.short_name || 'SEC' }}
                        </h1>
                        <p class="mt-3 text-xl font-medium text-slate-800">{{ orgName }}</p>
                        <p class="mt-4 max-w-2xl text-base leading-relaxed text-text-secondary">{{ motto }}</p>
                        <p class="mt-4 max-w-2xl text-sm leading-relaxed text-text-secondary">
                            An association of more independent entities and professionals from academia, companies,
                            organizations, or governments, pooling together their resources to achieve a common goal.
                        </p>
                    </div>
                    <div class="flex justify-center lg:justify-end">
                        <div class="card-modern max-w-sm p-8 text-center">
                            <img
                                :src="branding?.parent_org?.logo_url || '/images/sec-logo.png'"
                                alt="SEC logo"
                                class="mx-auto max-h-36 object-contain"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <h2 class="font-display text-2xl font-bold text-slate-900">Consortium leadership</h2>
            <p class="mt-2 text-sm text-slate-600">Executive officers of the Software Engineering Consortium.</p>
            <ul class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <li v-for="officer in officers" :key="officer.uuid" class="rounded-2xl border border-slate-200 bg-white p-5">
                    <p class="text-xs font-semibold uppercase tracking-wide text-institutional">{{ officer.role_title }}</p>
                    <p class="mt-2 font-semibold text-slate-900">{{ officer.name }}</p>
                </li>
            </ul>
        </section>

        <section class="bg-surface-muted py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <h2 class="font-display text-2xl font-bold text-slate-900">SEC Journals</h2>
                        <p class="mt-2 text-sm text-slate-600">Five peer-reviewed journals under the consortium.</p>
                    </div>
                    <RouterLink to="/journals" class="text-sm font-semibold text-institutional hover:underline">View all</RouterLink>
                </div>
                <ul class="mt-6 grid gap-3 md:grid-cols-2">
                    <li v-for="journal in journals" :key="journal.uuid">
                        <RouterLink
                            :to="journal.landing_url"
                            class="block rounded-xl border border-slate-200 bg-white px-4 py-3 font-medium text-slate-900 transition hover:border-institutional/40 hover:text-institutional"
                        >
                            {{ journal.name }}
                        </RouterLink>
                    </li>
                </ul>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <h2 class="font-display text-2xl font-bold text-slate-900">Contact</h2>
            <div class="mt-4 space-y-2 text-sm text-slate-700">
                <p>Room 1, PG Lab, JUPEB Building Upstairs, Science Village,</p>
                <p>Nnamdi Azikiwe University Awka, Anambra State, Nigeria.</p>
                <p>
                    Email:
                    <a :href="`mailto:${contact?.email || 'info@secjournals.net'}`" class="text-institutional hover:underline">
                        {{ contact?.email || 'info@secjournals.net' }}
                    </a>
                </p>
                <p>+234-8035410260</p>
                <p>+234-7035616299</p>
            </div>
            <RouterLink to="/contact" class="btn-institutional mt-6 inline-flex">Contact form</RouterLink>
        </section>
    </div>
</template>
