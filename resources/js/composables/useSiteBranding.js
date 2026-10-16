import { onMounted, ref } from 'vue';
import { publicApi } from '../api/client';

const branding = ref(null);
const sicama = ref(null);
let brandingPromise = null;
let sicamaPromise = null;

const defaults = {
    site_name: 'SEC Journals',
    site_tagline: 'Software Engineering Consortium Journals',
    site_logo_url: '/images/sec-logo.png',
    journal_full_name: 'Software Engineering Consortium Journals',
    parent_org: {
        short_name: 'SEC',
        full_name: 'Software Engineering Consortium',
        motto: 'Fusion of Academia and Industry to provide resources for productivity and advancement of the society.',
        logo_url: '/images/sec-logo.png',
    },
};

export function useSiteBranding() {
    async function loadBranding() {
        if (branding.value) {
            return branding.value;
        }

        if (!brandingPromise) {
            brandingPromise = publicApi()
                .get('/site-branding')
                .then(({ data }) => {
                    branding.value = {
                        ...defaults,
                        ...data.data,
                        parent_org: {
                            ...defaults.parent_org,
                            ...(data.data?.parent_org || {}),
                        },
                    };

                    if (!branding.value.site_logo_url) {
                        branding.value.site_logo_url = defaults.site_logo_url;
                    }

                    if (!branding.value.parent_org?.logo_url) {
                        branding.value.parent_org.logo_url = defaults.parent_org.logo_url;
                    }

                    return branding.value;
                })
                .catch(() => {
                    branding.value = defaults;
                    return branding.value;
                });
        }

        return brandingPromise;
    }

    async function loadSicama() {
        if (sicama.value) {
            return sicama.value;
        }

        if (!sicamaPromise) {
            sicamaPromise = publicApi()
                .get('/sec')
                .then(({ data }) => {
                    sicama.value = data.data;
                    return sicama.value;
                })
                .catch(() => null);
        }

        return sicamaPromise;
    }

    onMounted(() => {
        loadBranding();
    });

    return {
        branding,
        sicama,
        loadBranding,
        loadSicama,
        defaults,
    };
}
