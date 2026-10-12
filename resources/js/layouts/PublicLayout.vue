<script setup>
import { onMounted, ref } from 'vue';
import { RouterLink, RouterView } from 'vue-router';
import { Bars3Icon, ChevronDownIcon, LockClosedIcon } from '@heroicons/vue/24/outline';
import SiteBanner from '../components/layout/SiteBanner.vue';
import MobileNavDrawer from '../components/layout/MobileNavDrawer.vue';
import SiteFooter from '../components/layout/SiteFooter.vue';
import { useSiteBranding } from '../composables/useSiteBranding';
import { publicApi } from '../api/client';

const mobileNavOpen = ref(false);
const journalsOpen = ref(false);
const journals = ref([]);
const { branding, loadBranding } = useSiteBranding();

const nav = [
    { to: '/', label: 'About' },
    { to: '/sec', label: 'SEC' },
    { to: '/news', label: 'News' },
    { to: '/events', label: 'Events' },
    { to: '/members', label: 'Members' },
    { to: '/downloads', label: 'Library' },
    { to: '/articles', label: 'Articles' },
    { to: '/contact', label: 'Contact' },
];

onMounted(async () => {
    await loadBranding();
    try {
        const { data } = await publicApi().get('/journals');
        journals.value = data.data || [];
    } catch {
        journals.value = [];
    }
});

function closeMobileNav() {
    mobileNavOpen.value = false;
    journalsOpen.value = false;
}
</script>

<template>
    <div class="flex min-h-screen flex-col bg-white">
        <SiteBanner />
        <header class="glass-header sticky top-0 z-50">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-3.5 sm:gap-6 sm:px-6 lg:px-8">
                <div class="flex min-w-0 items-center gap-2 sm:gap-3">
                    <button
                        type="button"
                        class="rounded-lg p-2 text-white/80 hover:bg-white/10 lg:hidden"
                        aria-label="Open navigation menu"
                        @click="mobileNavOpen = true"
                    >
                        <Bars3Icon class="size-6" />
                    </button>
                    <RouterLink to="/" class="group flex min-w-0 shrink-0 items-center gap-3">
                        <span class="flex size-10 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-white ring-1 ring-white/20 transition group-hover:bg-white/95">
                            <img
                                :src="branding?.parent_org?.logo_url || branding?.site_logo_url || '/images/sec-logo.png'"
                                :alt="`${branding?.parent_org?.short_name || 'SEC'} logo`"
                                class="size-9 object-contain p-0.5"
                                @error="$event.target.src = '/images/sec-logo.png'"
                            />
                        </span>
                        <span class="min-w-0 leading-tight">
                            <span class="block truncate font-display text-lg font-bold tracking-tight text-white">
                                {{ branding?.site_name || 'SEC Journals' }}
                            </span>
                            <span class="hidden truncate text-[11px] text-white/70 sm:block">
                                {{ branding?.parent_org?.short_name || 'SEC' }}
                            </span>
                        </span>
                    </RouterLink>
                </div>

                <nav class="hidden flex-1 items-center justify-center gap-1 lg:flex">
                    <RouterLink
                        v-for="item in nav.slice(0, 2)"
                        :key="item.to"
                        v-slot="{ href, navigate, isActive, isExactActive }"
                        :to="item.to"
                        custom
                    >
                        <a
                            :href="href"
                            class="rounded-lg px-3 py-2 text-sm font-medium transition xl:px-3.5"
                            :class="(item.to === '/' ? isExactActive : isActive)
                                ? 'bg-white/10 text-white'
                                : 'text-white/75 hover:bg-white/10 hover:text-white'"
                            @click="navigate"
                        >
                            {{ item.label }}
                        </a>
                    </RouterLink>

                    <div class="relative" @mouseenter="journalsOpen = true" @mouseleave="journalsOpen = false">
                        <button
                            type="button"
                            class="inline-flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium text-white/75 transition hover:bg-white/10 hover:text-white xl:px-3.5"
                            :class="journalsOpen ? 'bg-white/10 text-white' : ''"
                            @click="journalsOpen = !journalsOpen"
                        >
                            Journals
                            <ChevronDownIcon class="size-4" />
                        </button>
                        <div
                            v-show="journalsOpen"
                            class="absolute left-0 top-full z-50 w-80 rounded-xl border border-slate-200 bg-white py-2 shadow-xl"
                        >
                            <RouterLink
                                to="/journals"
                                class="block px-4 py-2 text-sm font-semibold text-institutional hover:bg-slate-50"
                                @click="journalsOpen = false"
                            >
                                All SEC Journals
                            </RouterLink>
                            <div class="my-1 border-t border-slate-100" />
                            <RouterLink
                                v-for="journal in journals"
                                :key="journal.uuid"
                                :to="journal.landing_url"
                                class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-institutional"
                                @click="journalsOpen = false"
                            >
                                {{ journal.name }}
                            </RouterLink>
                        </div>
                    </div>

                    <RouterLink
                        v-for="item in nav.slice(2)"
                        :key="item.to"
                        v-slot="{ href, navigate, isActive }"
                        :to="item.to"
                        custom
                    >
                        <a
                            :href="href"
                            class="rounded-lg px-3 py-2 text-sm font-medium transition xl:px-3.5"
                            :class="isActive ? 'bg-white/10 text-white' : 'text-white/75 hover:bg-white/10 hover:text-white'"
                            @click="navigate"
                        >
                            {{ item.label }}
                        </a>
                    </RouterLink>
                </nav>

                <div class="flex shrink-0 items-center gap-2 sm:gap-3">
                    <RouterLink
                        to="/member/login"
                        class="hidden items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-white/80 transition hover:bg-white/10 hover:text-white sm:inline-flex"
                    >
                        <LockClosedIcon class="size-4" aria-hidden="true" />
                        <span class="hidden md:inline">Member Login</span>
                        <span class="md:hidden">Login</span>
                    </RouterLink>
                    <RouterLink to="/member/register" class="btn-gold !px-4 !py-2.5 text-sm sm:!px-5">
                        <span class="hidden sm:inline">Join Us / Register</span>
                        <span class="sm:hidden">Join</span>
                    </RouterLink>
                </div>
            </div>
        </header>

        <MobileNavDrawer :open="mobileNavOpen" title="Menu" panel-class="bg-institutional-dark" header-class="border-white/10" title-class="text-white" close-button-class="text-white/80 hover:bg-white/10" @close="closeMobileNav">
            <div class="space-y-1 p-4">
                <RouterLink
                    v-for="item in nav"
                    :key="item.to"
                    :to="item.to"
                    class="block rounded-lg px-3 py-2.5 text-sm font-medium text-white/85 hover:bg-white/10"
                    @click="closeMobileNav"
                >
                    {{ item.label }}
                </RouterLink>
                <p class="px-3 pt-4 text-xs font-semibold uppercase tracking-wide text-white/50">Journals</p>
                <RouterLink
                    to="/journals"
                    class="block rounded-lg px-3 py-2.5 text-sm font-medium text-white/85 hover:bg-white/10"
                    @click="closeMobileNav"
                >
                    All journals
                </RouterLink>
                <RouterLink
                    v-for="journal in journals"
                    :key="journal.uuid"
                    :to="journal.landing_url"
                    class="block rounded-lg px-3 py-2 text-sm text-white/75 hover:bg-white/10"
                    @click="closeMobileNav"
                >
                    {{ journal.name }}
                </RouterLink>
            </div>
        </MobileNavDrawer>

        <main class="flex-1">
            <RouterView />
        </main>
        <SiteFooter />
    </div>
</template>
