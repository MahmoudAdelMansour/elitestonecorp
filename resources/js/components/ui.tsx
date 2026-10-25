import { Head, Link, usePage } from '@inertiajs/react';
import { ArrowRight, ChevronRight } from 'lucide-react';
import type { ComponentProps, ReactNode } from 'react';
import { cn } from '@/lib/cn';
import { useI18n } from '@/lib/i18n';
import type { SharedProps } from '@/types';

type Variant = 'primary' | 'secondary' | 'light' | 'outline-light' | 'ghost';

const variants: Record<Variant, string> = {
    primary: 'bg-navy-700 text-white shadow-lg shadow-navy-900/15 hover:bg-navy-800',
    secondary: 'border border-navy-200 bg-white text-navy-800 hover:border-navy-400 hover:bg-navy-50',
    light: 'bg-white text-navy-800 shadow-lg shadow-navy-950/20 hover:bg-stone-100',
    'outline-light': 'border border-white/40 text-white hover:border-white hover:bg-white/10',
    ghost: 'px-0! text-navy-700 hover:text-navy-900',
};

export function buttonClass(variant: Variant = 'primary', className?: string) {
    return cn(
        'group inline-flex min-h-12 cursor-pointer items-center justify-center gap-2.5 rounded-full px-6 text-[0.95rem] font-semibold transition duration-200 ease-out disabled:cursor-not-allowed disabled:opacity-60',
        variants[variant],
        className,
    );
}

export function Arrow({ className }: { className?: string }) {
    return <ArrowRight aria-hidden="true" className={cn('size-4 transition-transform duration-200 group-hover:translate-x-1 rtl:-scale-x-100 rtl:group-hover:-translate-x-1', className)} />;
}

export function ButtonLink({ variant = 'primary', className, arrow = false, children, ...props }: ComponentProps<typeof Link> & { variant?: Variant; arrow?: boolean }) {
    return (
        <Link className={buttonClass(variant, className)} {...props}>
            {children}
            {arrow && <Arrow />}
        </Link>
    );
}

export function SectionHeader({ eyebrow, title, subtitle, align = 'start', light = false, className }: { eyebrow?: string; title: string; subtitle?: string | null; align?: 'start' | 'center'; light?: boolean; className?: string }) {
    return (
        <div data-reveal className={cn('max-w-3xl', align === 'center' && 'mx-auto text-center', className)}>
            {eyebrow && <p className={cn('eyebrow', light && 'text-stone-300', align === 'center' && 'justify-center')}>{eyebrow}</p>}
            <h2 className={cn('mt-4 text-3xl leading-tight font-semibold sm:text-4xl lg:text-[2.75rem]', light ? 'text-white' : 'text-navy-900')}>{title}</h2>
            {subtitle && <p className={cn('mt-5 text-lg leading-relaxed', light ? 'text-navy-100/80' : 'text-stone-600')}>{subtitle}</p>}
        </div>
    );
}

/** Angled navy/greige shards — the signature graphic from the Elite Stone company profile. */
export function Shards({ className, tone = 'light' }: { className?: string; tone?: 'light' | 'dark' }) {
    return (
        <svg viewBox="0 0 400 160" preserveAspectRatio="none" aria-hidden="true" className={cn('pointer-events-none', className)}>
            <polygon points="0,160 400,160 400,40" className={tone === 'light' ? 'fill-stone-50' : 'fill-navy-900'} />
            <polygon points="140,160 400,160 400,95" className={tone === 'light' ? 'fill-stone-200' : 'fill-navy-700'} opacity="0.9" />
            <polygon points="0,160 120,160 0,110" className="fill-navy-700" opacity={tone === 'light' ? 0.9 : 0.5} />
        </svg>
    );
}

export function Seo() {
    const { meta, url } = usePage<SharedProps>().props;
    return (
        <Head>
            <title>{meta.title}</title>
            <meta name="description" content={meta.description} head-key="description" />
            <meta property="og:title" content={meta.title} head-key="og:title" />
            <meta property="og:description" content={meta.description} head-key="og:description" />
            <meta property="og:image" content={meta.image} head-key="og:image" />
            <meta property="og:url" content={url} head-key="og:url" />
            <link rel="canonical" href={url} head-key="canonical" />
        </Head>
    );
}

export function Breadcrumbs({ items, light = true }: { items: { label: string; href?: string }[]; light?: boolean }) {
    const { t, href } = useI18n();
    const all = [{ label: t('common.home'), href: href('/') }, ...items];
    return (
        <nav aria-label={t('common.breadcrumb')}>
            <ol className={cn('flex flex-wrap items-center gap-1.5 text-sm', light ? 'text-navy-100/80' : 'text-stone-500')}>
                {all.map((item, index) => (
                    <li key={index} className="flex items-center gap-1.5">
                        {index > 0 && <ChevronRight aria-hidden="true" className="size-3.5 opacity-60 rtl:-scale-x-100" />}
                        {item.href && index < all.length - 1 ? (
                            <Link href={item.href} className={cn('transition', light ? 'hover:text-white' : 'hover:text-navy-800')}>
                                {item.label}
                            </Link>
                        ) : (
                            <span aria-current="page" className={light ? 'text-white' : 'text-navy-900'}>
                                {item.label}
                            </span>
                        )}
                    </li>
                ))}
            </ol>
        </nav>
    );
}

export function PageHero({ title, subtitle, image, eyebrow, crumbs, children }: { title: string; subtitle?: string | null; image?: string | null; eyebrow?: string; crumbs: { label: string; href?: string }[]; children?: ReactNode }) {
    return (
        <section className="relative isolate overflow-hidden bg-navy-900 pt-36 pb-28 text-white sm:pt-44 sm:pb-36">
            {image && <img src={image} alt="" className="absolute inset-0 -z-20 size-full object-cover opacity-35" fetchPriority="high" />}
            <div className="absolute inset-0 -z-10 bg-gradient-to-r from-navy-950/95 via-navy-900/85 to-navy-900/40 rtl:bg-gradient-to-l" />
            <div className="mark-mask absolute -end-16 top-24 -z-10 size-80 text-white/[0.04] sm:size-[28rem]" aria-hidden="true" />
            <div className="container-site">
                <Breadcrumbs items={crumbs} />
                {eyebrow && <p className="eyebrow mt-8 text-stone-300">{eyebrow}</p>}
                <h1 className="mt-5 max-w-4xl text-4xl leading-[1.1] font-semibold sm:text-5xl lg:text-6xl">{title}</h1>
                {subtitle && <p className="mt-6 max-w-2xl text-lg leading-relaxed text-navy-100/85">{subtitle}</p>}
                {children}
            </div>
            <Shards className="absolute inset-x-0 bottom-0 h-16 w-full sm:h-24 rtl:-scale-x-100" />
        </section>
    );
}
