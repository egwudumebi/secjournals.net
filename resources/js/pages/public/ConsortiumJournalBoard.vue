<script setup>
import { onMounted, ref, watch } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import { publicApi } from '../../api/client';

const route = useRoute();
const journal = ref(null);
const members = ref([]);
const loading = ref(true);
const error = ref('');

async function load() {
    loading.value = true;
    error.value = '';
    try {
        const { data } = await publicApi().get(`/journals/${route.params.slug}/editorial-board`);
        journal.value = data.data?.journal || null;
        members.value = data.data?.members || [];
    } catch {
        error.value = 'Editorial board not found.';
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
        <h1 class="mt-4 font-display text-3xl font-bold text-slate-900">Editorial Board</h1>
        <p v-if="journal" class="mt-2 text-slate-600">{{ journal.name }}</p>

        <div v-if="loading" class="mt-8 text-slate-500">Loading…</div>
        <div v-else-if="error" class="mt-8 text-slate-500">{{ error }}</div>
        <ul v-else class="mt-8 grid gap-4 sm:grid-cols-2">
            <li v-for="member in members" :key="member.uuid" class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-institutional">{{ member.role_title }}</p>
                <p class="mt-2 text-lg font-semibold text-slate-900">{{ member.name }}</p>
                <p v-if="member.affiliation" class="mt-1 text-sm text-slate-600">{{ member.affiliation }}</p>
                <p v-if="member.bio" class="mt-3 text-sm text-slate-600">{{ member.bio }}</p>
            </li>
        </ul>
    </div>
</template>
