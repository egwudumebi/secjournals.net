const IMAGES = {
    welcome: '/images/hero-welcome.jpg',
    journal: '/images/hero-journal.jpg',
    conference: '/images/hero-conference.jpg',
    membership: '/images/hero-membership.jpg',
};

function formatCfpDeadline(dateString) {
    if (!dateString) {
        return 'Open for submissions';
    }

    return `Closes ${new Date(dateString).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    })}`;
}

/**
 * @param {object} branding
 * @returns {Array<object>}
 */
export function buildDefaultHeroSlides(branding = {}) {
    const siteName = branding.site_name || 'SEC Journals';
    const journalName = branding.journal_full_name || branding.site_tagline || 'Software Engineering Consortium Journals';
    const parentOrg = branding.parent_org?.short_name || 'SEC';
    const motto = branding.parent_org?.motto
        || 'Fusion of Academia and Industry for productivity and advancement of society.';

    return [
        {
            id: 'welcome',
            theme: 'light',
            eyebrow: siteName,
            title: 'Engineering Scholarship for Industry and Society',
            highlight: null,
            suffix: null,
            compactTitle: true,
            description: `${journalName} — peer-reviewed journals of the ${parentOrg}. ${motto}`,
            ctaLabel: 'Explore Journals',
            ctaTo: '/journals',
            secondaryCtaLabel: 'Become a Member',
            secondaryCtaTo: '/member/register',
            imageUrl: IMAGES.welcome,
            imagePosition: 'right center',
            accent: 'gold',
        },
        {
            id: 'journal',
            theme: 'dark',
            eyebrow: 'Research Journals',
            title: journalName,
            highlight: null,
            suffix: null,
            compactTitle: true,
            description: `Browse published articles across software, AI, and engineering journals, and discover open calls for papers from ${siteName}.`,
            ctaLabel: 'Browse Journals',
            ctaTo: '/journals',
            secondaryCtaLabel: 'Author Guidelines',
            secondaryCtaTo: '/journal/author-guidelines',
            imageUrl: IMAGES.journal,
            imagePosition: 'center',
            accent: 'gold',
        },
        {
            id: 'conference',
            theme: 'dark',
            eyebrow: 'Conferences & Events',
            title: 'Where Ideas',
            highlight: 'Take the Stage',
            suffix: null,
            description: 'Conferences, workshops, and consortia gatherings advancing software engineering practice across Nigeria and beyond.',
            ctaLabel: 'View Events',
            ctaTo: '/events',
            secondaryCtaLabel: 'Register Now',
            secondaryCtaTo: '/events',
            imageUrl: IMAGES.conference,
            imagePosition: 'center',
            accent: 'gold',
        },
        {
            id: 'membership',
            theme: 'dark',
            eyebrow: 'Membership',
            title: 'Join',
            highlight: parentOrg,
            suffix: ' today',
            description: 'Access digital credentials, exclusive resources, event privileges, and the full member portal.',
            ctaLabel: 'Join Us Today',
            ctaTo: '/member/register',
            secondaryCtaLabel: 'Learn About SEC',
            secondaryCtaTo: '/sec',
            imageUrl: IMAGES.membership,
            imagePosition: 'center',
            accent: 'gold',
        },
    ];
}

/**
 * @param {Array<object>} calls
 * @returns {Array<object>}
 */
export function buildCfpHeroSlides(calls = []) {
    return calls.slice(0, 2).map((call) => ({
        id: `cfp-${call.uuid}`,
        theme: 'dark',
        eyebrow: 'Call for Papers',
        title: call.title,
        highlight: null,
        suffix: null,
        compactTitle: true,
        description: call.excerpt || formatCfpDeadline(call.closes_at),
        ctaLabel: 'Browse Journal',
        ctaTo: '/journal',
        secondaryCtaLabel: 'Submit Manuscript',
        secondaryCtaTo: '/member/journal/submit',
        imageUrl: IMAGES.conference,
        imagePosition: 'center',
        accent: 'gold',
    }));
}

/**
 * @param {object} options
 * @param {Array<object>} [options.calls]
 * @param {object} [options.branding]
 * @returns {Array<object>}
 */
export function resolveHeroSlides({ calls = [], branding = {} } = {}) {
    const defaults = buildDefaultHeroSlides(branding);
    const dynamic = buildCfpHeroSlides(calls);

    if (dynamic.length === 0) {
        return defaults;
    }

    return [defaults[0], ...dynamic, ...defaults.slice(1)].slice(0, 6);
}
