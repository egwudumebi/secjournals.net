<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import { publicApi } from '../../api/client';
import { useOrgInfo } from '../../composables/useOrgInfo';

const route = useRoute();
const { journal: hub, loadOrgInfo } = useOrgInfo();
const journal = ref(null);

const guidelines = computed(() => hub.value?.author_guidelines || {});
const sections = computed(() => guidelines.value?.sections || []);

onMounted(async () => {
    await loadOrgInfo();
    await loadJournal();
});

watch(() => route.params.slug, loadJournal);

async function loadJournal() {
    const { data } = await publicApi().get(`/journals/${route.params.slug}`);
    journal.value = data.data;
}
</script>

<template>
    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        <RouterLink v-if="journal" :to="journal.landing_url" class="text-sm font-medium text-institutional hover:underline">
            ← {{ journal.name }}
        </RouterLink>
        <h1 class="mt-4 font-display text-3xl font-bold text-slate-900">Author Guidelines</h1>
        <p v-if="journal" class="mt-2 text-slate-600">{{ journal.name }}</p>
        <p class="mt-6 leading-relaxed text-slate-700">{{ guidelines.intro }}</p>
        <div v-for="section in sections" :key="section.title" class="mt-8">
            <h2 class="font-display text-xl font-bold text-slate-900">{{ section.title }}</h2>
            <p class="mt-2 leading-relaxed text-slate-700">{{ section.body }}</p>
        </div>
        <RouterLink to="/member/journal/submit" class="btn-institutional mt-10 inline-flex">Submit manuscript</RouterLink>
    </div>
</template>
