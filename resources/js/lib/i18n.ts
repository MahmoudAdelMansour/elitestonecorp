import { usePage } from '@inertiajs/react';
import type { SharedProps } from '@/types';

type Replacements = Record<string, string | number>;

function lookup(source: unknown, key: string): unknown {
    return key.split('.').reduce<unknown>((value, part) => (value && typeof value === 'object' ? (value as Record<string, unknown>)[part] : undefined), source);
}

function replace(text: string, replacements: Replacements = {}): string {
    return Object.entries(replacements).reduce((out, [name, value]) => out.replaceAll(`:${name}`, String(value)), text);
}

/** Laravel-style pluralisation: "{0} none|{1} one|[2,*] :count many". */
function choose(text: string, count: number): string {
    for (const segment of text.split('|')) {
        const exact = segment.match(/^\{(\d+)\}\s?(.*)$/s);
        if (exact && Number(exact[1]) === count) return exact[2];
        const range = segment.match(/^\[(\d+),(\d+|\*)\]\s?(.*)$/s);
        if (range && count >= Number(range[1]) && (range[2] === '*' || count <= Number(range[2]))) return range[3];
    }
    return text.split('|').pop() ?? text;
}

export function useI18n() {
    const { t: dictionary, locale, dir } = usePage<SharedProps>().props;

    const t = (key: string, replacements?: Replacements): string => {
        const value = lookup(dictionary, key);
        return typeof value === 'string' ? replace(value, replacements) : key;
    };

    const tc = (key: string, count: number, replacements?: Replacements): string =>
        replace(choose(t(key), count), { count, ...replacements });

    function list<T>(key: string): T[] {
        const value = lookup(dictionary, key);
        if (Array.isArray(value)) return value as T[];
        return value && typeof value === 'object' ? (Object.values(value) as T[]) : [];
    }

    /** Prefix an internal path with the active locale: href('/projects') -> /ar/projects */
    const href = (path = ''): string => `/${locale}${path === '/' ? '' : path}`;

    const number = (value: number): string => new Intl.NumberFormat(locale === 'ar' ? 'ar-EG' : 'en-US').format(value);

    return { t, tc, list, href, number, locale, dir, isRtl: dir === 'rtl' };
}
