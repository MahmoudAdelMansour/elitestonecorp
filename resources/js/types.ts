export type Locale = 'en' | 'ar';

export interface Meta {
    title: string;
    description: string;
    image: string;
    schema: Record<string, unknown>[];
}

export interface SiteInfo {
    phone: string | null;
    whatsapp: string | null;
    email: string | null;
    address: string | null;
    workingHours: string | null;
    mapQuery: string | null;
    facebook: string | null;
    instagram: string | null;
    linkedin: string | null;
    foundedYear: number;
    profileUrl: string | null;
}

export interface SharedProps {
    locale: Locale;
    dir: 'ltr' | 'rtl';
    alternateUrl: string;
    url: string;
    t: Record<string, unknown>;
    site: SiteInfo;
    navServices: { slug: string; title: string; group: ServiceGroup }[];
    meta: Meta;
    [key: string]: unknown;
}

export type ServiceGroup = 'systems' | 'contracting';

export interface Service {
    id: number;
    slug: string;
    group: ServiceGroup;
    title: string;
    excerpt: string | null;
    icon: string;
    image: string | null;
}

export interface Sector {
    id: number;
    slug: string;
    name: string;
    description: string | null;
    icon: string;
    image: string | null;
    projectsCount?: number;
}

export interface Partner {
    id: number;
    name: string;
    logo: string | null;
    website: string | null;
}

export interface Project {
    id: number;
    slug: string;
    title: string;
    summary: string | null;
    year: number | null;
    location: string | null;
    area: string | null;
    cover: string | null;
    featured: boolean;
    sector?: { slug: string; name: string } | null;
    client?: string | null;
    services?: { slug: string; title: string }[];
}

export interface Stats {
    years: number;
    projects: number;
    sectors: number;
    partners: number;
}

declare module '@inertiajs/core' {
    export interface InertiaConfig {
        sharedPageProps: SharedProps;
        flashDataType: { inquiry?: { reference: string } };
    }
}
