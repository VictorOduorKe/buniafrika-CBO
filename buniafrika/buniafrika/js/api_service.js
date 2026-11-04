/**
 * BuniAfrika Dynamic CMS Client API Service
 * Provides client-side helpers to consume database-driven content.
 */

(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        define([], factory);
    } else if (typeof module === 'object' && module.exports) {
        module.exports = factory();
    } else {
        root.BuniafrikaAPI = factory();
    }
}(typeof self !== 'undefined' ? self : this, function () {
    'use strict';

    // Base API URL Resolver
    const resolveBaseUrl = () => {
        if (typeof window !== 'undefined' && window.BUNIAFRIKA_API_URL) {
            return window.BUNIAFRIKA_API_URL.replace(/\/+$/, '');
        }

        const origin = (typeof window !== 'undefined' && window.location) ? window.location.origin : '';
        const hostname = (typeof window !== 'undefined' && window.location) ? window.location.hostname : '';
        const pathname = (typeof window !== 'undefined' && window.location) ? window.location.pathname : '';

        // Check if running on localhost or dev environment
        const isLocal = hostname === 'localhost' || hostname === '127.0.0.1' || hostname === '::1' || hostname.endsWith('.local') || hostname.endsWith('.test');
        if (isLocal) {
            if (pathname.includes('/buniafrika/')) {
                const rootPrefix = pathname.substring(0, pathname.indexOf('/buniafrika/'));
                return `${origin}${rootPrefix}/buniafrika.co.ke/public/api.php?endpoint=`;
            }
            return `${origin}/api.php?endpoint=`;
        }

        // Production default (backend subdomain or API route)
        if (typeof window !== 'undefined' && window.location.hostname.endsWith('buniafrika.co.ke')) {
            return `${origin}/api`;
        }

        return 'https://buniafrika.co.ke/api';
    };

    const request = async (endpoint, options = {}) => {
        const base = resolveBaseUrl();
        const separator = base.includes('endpoint=') ? '' : '/';
        const url = `${base}${separator}${endpoint.replace(/^\/+/, '')}`;

        const defaultHeaders = {
            'Accept': 'application/json',
        };

        const config = {
            ...options,
            headers: {
                ...defaultHeaders,
                ...(options.headers || {}),
            },
        };

        try {
            const res = await fetch(url, config);
            const data = await res.json();
            if (!res.ok) {
                throw new Error(data.message || `API request failed with HTTP ${res.status}`);
            }
            return data;
        } catch (err) {
            console.error(`[BuniafrikaAPI] Error requesting ${endpoint}:`, err);
            return {
                status: 'error',
                message: err.message || 'Network request failed',
                data: [],
            };
        }
    };

    return {
        getBaseUrl: resolveBaseUrl,

        // Thematic Areas
        getThematicAreas: () => request('thematic-areas'),
        getThematicArea: (idOrSlug) => request(`thematic-areas/${idOrSlug}`),

        // Organisational Objectives
        getObjectives: () => request('objectives'),

        // Publications and Reports
        getPublications: (category = '') => {
            const query = category ? `?category=${encodeURIComponent(category)}` : '';
            return request(`publications${query}`);
        },
        getPublication: (id) => request(`publications/${id}`),

        // Testimonials
        getTestimonials: () => request('testimonials'),

        // FAQs
        getFaqs: (category = '') => {
            const query = category ? `?category=${encodeURIComponent(category)}` : '';
            return request(`faqs${query}`);
        },

        // Partnerships
        getPartners: () => request('partners'),

        // Volunteer Opportunities
        getVolunteerOpportunities: () => request('volunteer-opportunities'),
        getVolunteerOpportunity: (id) => request(`volunteer-opportunities/${id}`),

        // Volunteer Application Submission
        submitVolunteerApplication: async (formOrData) => {
            const base = resolveBaseUrl();
            const separator = base.includes('endpoint=') ? '' : '/';
            const url = `${base}${separator}volunteer-applications`;

            let body;
            const headers = { 'Accept': 'application/json' };

            if (formOrData instanceof FormData) {
                body = formOrData;
            } else if (formOrData instanceof HTMLFormElement) {
                body = new FormData(formOrData);
            } else {
                body = JSON.stringify(formOrData);
                headers['Content-Type'] = 'application/json';
            }

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: headers,
                    body: body,
                });
                const data = await res.json();
                if (!res.ok) {
                    throw new Error(data.message || `Submission failed with HTTP ${res.status}`);
                }
                return data;
            } catch (err) {
                console.error('[BuniafrikaAPI] Error submitting volunteer application:', err);
                return {
                    status: 'error',
                    message: err.message || 'Submission failed. Please try again.',
                };
            }
        },

        // Our Story / Milestones
        getOurStory: () => request('our-story'),

        // Core Values
        getCoreValues: () => request('core-values'),

        // Achievements and Impact
        getAchievements: () => request('achievements'),
    };
}));

