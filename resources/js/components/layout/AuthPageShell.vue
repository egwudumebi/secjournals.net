<script setup>
import { onMounted } from 'vue';
import { RouterLink } from 'vue-router';
import SiteFooter from './SiteFooter.vue';
import { useSiteBranding } from '../../composables/useSiteBranding';

defineProps({
    title: {
        type: String,
        required: true,
    },
    subtitle: {
        type: String,
        default: '',
    },
    bullets: {
        type: Array,
        default: () => [],
    },
});

const { branding, loadBranding } = useSiteBranding();
onMounted(() => loadBranding());
</script>

<template>
    <div class="flex min-h-screen flex-col bg-surface-muted">
        <header class="border-b border-slate-200/80 bg-white">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <RouterLink to="/" class="group flex items-center gap-2.5">
                    <span class="flex size-10 items-center justify-center overflow-hidden rounded-xl bg-white ring-1 ring-slate-200">
                        <img
                            :src="branding?.site_logo_url || branding?.parent_org?.logo_url || '/images/sec-logo.png'"
                            alt="SEC logo"
                            class="size-9 object-contain p-0.5"
                            @error="$event.target.src = '/images/sec-logo.png'"
                        />
                    </span>
                    <span class="font-display text-lg font-bold text-institutional-dark">
                        {{ branding?.site_name || 'SEC Journals' }}
                    </span>
                </RouterLink>
                <RouterLink to="/" class="text-sm font-medium text-institutional transition hover:text-institutional-dark">
                    ← Back to website
                </RouterLink>
            </div>
        </header>

        <div class="flex flex-1 flex-col lg:flex-row">
            <aside class="relative hidden overflow-hidden bg-gradient-to-br from-black via-institutional to-[#2a2a32] lg:flex lg:w-[42%] xl:w-[45%]">
                <div class="pointer-events-none absolute inset-0 opacity-40" aria-hidden="true">
                    <div class="absolute -right-20 -top-20 size-72 rounded-full bg-accent-gold/30 blur-3xl" />
                    <div class="absolute -bottom-24 -left-16 size-64 rounded-full bg-white/5 blur-3xl" />
                </div>
                <div class="relative flex flex-col justify-center px-10 py-16 xl:px-16">
                    <p class="label-caps text-white/70">Member Portal</p>
                    <h1 class="mt-4 font-display text-3xl font-bold leading-tight text-white xl:text-4xl">
                        {{ title }}
                    </h1>
                    <p v-if="subtitle" class="mt-4 text-base leading-relaxed text-slate-200">
                        {{ subtitle }}
                    </p>
                    <ul v-if="bullets.length" class="mt-8 space-y-3">
                        <li v-for="bullet in bullets" :key="bullet" class="flex items-start gap-3 text-sm text-slate-200">
                            <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-accent-gold" />
                            {{ bullet }}
                        </li>
                    </ul>
                    <slot name="aside" />
                </div>
            </aside>

            <main class="flex flex-1 items-center justify-center px-4 py-10 sm:px-6 lg:px-10 lg:py-14">
                <div class="w-full max-w-md">
                    <slot />
                </div>
            </main>
        </div>

        <SiteFooter />
    </div>
</template>
