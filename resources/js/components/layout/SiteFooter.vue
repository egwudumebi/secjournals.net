<script setup>
import { computed, onMounted, ref } from 'vue';
import { RouterLink } from 'vue-router';
import { EnvelopeIcon } from '@heroicons/vue/24/outline';
import { useSiteBranding } from '../../composables/useSiteBranding';
import { useOrgInfo } from '../../composables/useOrgInfo';
import { publicApi } from '../../api/client';

const { branding, loadBranding } = useSiteBranding();
const { social, contact, hasSocialLinks, loadOrgInfo } = useOrgInfo();

const email = ref('');
const submitting = ref(false);
const status = ref(null);

const siteName = computed(() => branding.value?.site_name || 'SEC Journals');
const parentName = computed(() => branding.value?.parent_org?.full_name || 'Software Engineering Consortium');
const parentShort = computed(() => branding.value?.parent_org?.short_name || 'SEC');
const contactEmail = computed(() => contact.value?.email || 'info@secjournals.net');
const watermark = computed(() => siteName.value.toLowerCase());

const exploreLinks = [
    { to: '/', label: 'About' },
    { to: '/sec', label: 'SEC' },
    { to: '/journals', label: 'Journals' },
    { to: '/articles', label: 'Articles' },
    { to: '/news', label: 'News' },
    { to: '/events', label: 'Events' },
];

const memberLinks = [
    { to: '/member/login', label: 'Member Login' },
    { to: '/member/register', label: 'Join / Register' },
    { to: '/members', label: 'Member Directory' },
    { to: '/verify', label: 'Verify Credential' },
    { to: '/downloads', label: 'Library' },
];

const missionLinks = [
    { to: '/journals', label: 'Browse Journals' },
    { to: '/articles/current-issue', label: 'Current Issue' },
    { to: '/journal/author-guidelines', label: 'Author Guidelines' },
    { to: '/sec', label: 'Consortium' },
    { to: '/contact', label: 'Contact Us' },
];

const socialItems = computed(() => {
    const links = social.value || {};

    return [
        { key: 'facebook', label: 'Facebook', href: links.facebook },
        { key: 'linkedin', label: 'LinkedIn', href: links.linkedin },
        { key: 'x', label: 'X', href: links.x },
        { key: 'instagram', label: 'Instagram', href: links.instagram },
        { key: 'youtube', label: 'YouTube', href: links.youtube },
    ].filter((item) => typeof item.href === 'string' && item.href.trim() !== '');
});

onMounted(() => {
    loadBranding();
    loadOrgInfo();
});

async function submitNewsletter() {
    const value = email.value.trim();
    if (!value || submitting.value) {
        return;
    }

    submitting.value = true;
    status.value = null;

    try {
        await publicApi().post('/contact', {
            name: value.split('@')[0] || 'Subscriber',
            email: value,
            subject: 'Newsletter signup',
            message: `Please add ${value} to the ${siteName.value} email updates list.`,
            category: 'newsletter',
        });
        status.value = { type: 'ok', text: 'Thanks — you are on the list.' };
        email.value = '';
    } catch {
        status.value = { type: 'err', text: 'Could not subscribe. Try again or email us.' };
    } finally {
        submitting.value = false;
    }
}
</script>

<template>
    <footer class="relative overflow-hidden border-t border-white/10 bg-institutional text-slate-300">
        <div class="relative z-10 mx-auto max-w-7xl px-4 pb-6 pt-16 sm:px-6 lg:px-8 lg:pt-20">
            <div class="grid gap-12 lg:grid-cols-12 lg:gap-10">
                <div class="lg:col-span-5 xl:col-span-5">
                    <p class="font-mono text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500">
                        Stay up to date with {{ parentShort }}
                    </p>
                    <h2 class="mt-4 max-w-md font-display text-3xl font-bold leading-tight tracking-tight text-white sm:text-4xl">
                        Sign up for email updates from {{ siteName }}
                    </h2>

                    <form class="mt-8 flex max-w-lg flex-col gap-3 sm:flex-row sm:items-stretch" @submit.prevent="submitNewsletter">
                        <label class="sr-only" for="footer-newsletter-email">Email address</label>
                        <input
                            id="footer-newsletter-email"
                            v-model="email"
                            type="email"
                            required
                            autocomplete="email"
                            placeholder="Email address"
                            class="min-w-0 flex-1 rounded-full border border-transparent bg-white px-5 py-3.5 text-sm text-slate-900 shadow-sm outline-none ring-1 ring-white/10 placeholder:text-slate-400 focus:ring-2 focus:ring-accent-gold/50"
                        />
                        <button
                            type="submit"
                            class="shrink-0 rounded-full bg-accent-gold px-6 py-3.5 text-xs font-bold uppercase tracking-[0.18em] text-white transition hover:bg-accent-hover disabled:opacity-60"
                            :disabled="submitting"
                        >
                            {{ submitting ? 'Sending…' : 'Submit' }}
                        </button>
                    </form>
                    <p class="mt-3 text-xs text-slate-500">Unsubscribe at any time.</p>
                    <p
                        v-if="status"
                        class="mt-2 text-xs font-medium"
                        :class="status.type === 'ok' ? 'text-emerald-400' : 'text-red-400'"
                    >
                        {{ status.text }}
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-8 sm:grid-cols-3 lg:col-span-7 lg:gap-6 xl:gap-10">
                    <div>
                        <h3 class="font-mono text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500">
                            Explore
                        </h3>
                        <ul class="mt-5 space-y-3">
                            <li v-for="link in exploreLinks" :key="link.to">
                                <RouterLink
                                    :to="link.to"
                                    class="text-[15px] font-medium text-white transition hover:text-accent-gold"
                                >
                                    {{ link.label }}
                                </RouterLink>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-mono text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500">
                            Members
                        </h3>
                        <ul class="mt-5 space-y-3">
                            <li v-for="link in memberLinks" :key="link.to">
                                <RouterLink
                                    :to="link.to"
                                    class="text-[15px] font-medium text-white transition hover:text-accent-gold"
                                >
                                    {{ link.label }}
                                </RouterLink>
                            </li>
                        </ul>
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <h3 class="font-mono text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500">
                            The consortium
                        </h3>
                        <ul class="mt-5 space-y-3">
                            <li v-for="link in missionLinks" :key="`${link.to}-${link.label}`">
                                <RouterLink
                                    :to="link.to"
                                    class="text-[15px] font-medium text-white transition hover:text-accent-gold"
                                >
                                    {{ link.label }}
                                </RouterLink>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mt-14 flex flex-col gap-4 border-t border-white/10 pt-6 sm:flex-row sm:items-center sm:justify-between">
                <p class="font-mono text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">
                    &copy; {{ new Date().getFullYear() }} {{ parentName }}
                </p>
                <div class="flex flex-wrap items-center gap-4">
                    <a
                        v-for="item in socialItems"
                        :key="item.key"
                        :href="item.href"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="font-mono text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-400 transition hover:text-accent-gold"
                    >
                        {{ item.label }}
                    </a>
                    <a
                        :href="`mailto:${contactEmail}`"
                        class="inline-flex items-center gap-2 font-mono text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-300 transition hover:text-accent-gold"
                    >
                        <EnvelopeIcon class="size-4" aria-hidden="true" />
                        {{ contactEmail }}
                    </a>
                    <span v-if="!hasSocialLinks" class="sr-only">No social profiles configured</span>
                </div>
            </div>
        </div>

        <div
            class="pointer-events-none relative z-0 select-none overflow-hidden pb-0 pt-2"
            aria-hidden="true"
        >
            <p
                class="translate-y-[38%] whitespace-nowrap text-center font-display text-[18vw] font-bold lowercase leading-none tracking-tight text-white/[0.06] sm:text-[16vw] lg:text-[14vw]"
            >
                {{ watermark }}
            </p>
        </div>
    </footer>
</template>
