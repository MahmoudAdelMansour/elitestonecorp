import { Link, usePage } from '@inertiajs/react';
import { ArrowUpRight, Award, HardHat, HeartHandshake, Lightbulb, Minus, Plus, ShieldCheck } from 'lucide-react';
import { useState } from 'react';
import Icon, { WhatsAppIcon } from '@/components/Icon';
import { whatsappUrl } from '@/components/Layout';
import { Arrow, buttonClass, SectionHeader, Shards } from '@/components/ui';
import { cn } from '@/lib/cn';
import { useI18n } from '@/lib/i18n';
import type { Partner, Project, Sector, Service, SharedProps, Stats } from '@/types';

export function ServiceCard({ service, index = 0, large = false }: { service: Service; index?: number; large?: boolean }) {
    const { t, href } = useI18n();
    return (
        <Link
            href={href(`/services/${service.slug}`)}
            data-reveal
            style={{ '--reveal-delay': `${(index % 3) * 90}ms` } as React.CSSProperties}
            className="group relative flex flex-col overflow-hidden rounded-3xl bg-white shadow-[0_1px_0_rgba(18,26,46,0.06),0_20px_40px_-24px_rgba(18,26,46,0.25)] ring-1 ring-stone-200/70 transition duration-300 hover:-translate-y-1 hover:shadow-[0_30px_60px_-28px_rgba(18,26,46,0.45)]"
        >
            <div className={cn('relative overflow-hidden bg-stone-200', large ? 'aspect-[16/10]' : 'aspect-[4/3]')}>
                {service.image && <img src={service.image} alt="" loading="lazy" className="size-full object-cover transition duration-700 ease-out group-hover:scale-105" />}
                <div className="absolute inset-0 bg-gradient-to-t from-navy-950/50 via-transparent to-transparent" />
                <span className="absolute start-5 bottom-5 inline-flex size-12 items-center justify-center rounded-2xl bg-white/95 text-navy-700 shadow-md backdrop-blur">
                    <Icon name={service.icon} className="size-6" />
                </span>
            </div>
            <div className="flex flex-1 flex-col p-6 sm:p-7">
                <h3 className="text-xl font-semibold text-navy-900 sm:text-2xl">{service.title}</h3>
                {service.excerpt && <p className="mt-3 flex-1 leading-relaxed text-stone-600">{service.excerpt}</p>}
                <span className="mt-6 inline-flex items-center gap-2 text-sm font-bold text-navy-700">
                    {t('common.learn_more')}
                    <Arrow />
                </span>
            </div>
        </Link>
    );
}

export function ProjectCard({ project, index = 0, tall = false }: { project: Project; index?: number; tall?: boolean }) {
    const { href } = useI18n();
    return (
        <Link
            href={href(`/projects/${project.slug}`)}
            data-reveal
            style={{ '--reveal-delay': `${(index % 3) * 90}ms` } as React.CSSProperties}
            className={cn('group relative isolate flex h-full overflow-hidden rounded-3xl bg-navy-900 text-white', tall ? 'min-h-[30rem]' : 'min-h-[22rem]')}
        >
            {project.cover && <img src={project.cover} alt="" loading="lazy" className="absolute inset-0 -z-10 size-full object-cover transition duration-700 ease-out group-hover:scale-105" />}
            <div className="absolute inset-0 -z-10 bg-gradient-to-t from-navy-950 via-navy-950/40 to-navy-950/0" />
            <div className="mt-auto w-full p-6 sm:p-7">
                <div className="flex flex-wrap items-center gap-2 text-xs font-semibold">
                    {project.year && <span className="rounded-full bg-white/15 px-3 py-1 backdrop-blur">{project.year}</span>}
                    {project.sector && <span className="rounded-full bg-white/15 px-3 py-1 backdrop-blur">{project.sector.name}</span>}
                </div>
                <h3 className="mt-4 text-xl leading-snug font-semibold sm:text-2xl">{project.title}</h3>
                <div className="mt-3 flex items-center justify-between gap-4 text-sm text-navy-100/80">
                    <span>{[project.client, project.location].filter(Boolean).join(' · ')}</span>
                    <span className="inline-flex size-10 shrink-0 items-center justify-center rounded-full bg-white text-navy-800 transition duration-300 group-hover:rotate-45 rtl:-scale-x-100">
                        <ArrowUpRight className="size-5" aria-hidden="true" />
                    </span>
                </div>
            </div>
        </Link>
    );
}

export function SectorGrid({ sectors }: { sectors: Sector[] }) {
    const { tc, href } = useI18n();
    return (
        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            {sectors.map((sector, i) => (
                <Link
                    key={sector.id}
                    href={`${href('/projects')}?sector=${sector.slug}`}
                    data-reveal
                    style={{ '--reveal-delay': `${(i % 3) * 80}ms` } as React.CSSProperties}
                    className="group relative isolate overflow-hidden rounded-3xl border border-white/10 bg-white/[0.04] p-7 transition duration-300 hover:border-white/25 hover:bg-white/[0.08]"
                >
                    {sector.image && <img src={sector.image} alt="" loading="lazy" className="absolute inset-0 -z-10 size-full object-cover opacity-0 transition duration-500 group-hover:opacity-20" />}
                    <span className="inline-flex size-12 items-center justify-center rounded-2xl bg-stone-200 text-navy-800">
                        <Icon name={sector.icon} className="size-6" />
                    </span>
                    <h3 className="mt-6 text-xl font-semibold text-white">{sector.name}</h3>
                    {sector.description && <p className="mt-3 leading-relaxed text-navy-100/75">{sector.description}</p>}
                    {sector.projectsCount !== undefined && (
                        <p className="mt-6 inline-flex items-center gap-2 text-sm font-bold text-stone-200">
                            {tc('common.projects_count', sector.projectsCount)}
                            <Arrow />
                        </p>
                    )}
                </Link>
            ))}
        </div>
    );
}

export function LogoWall({ logos, variant = 'grid' }: { logos: Partner[]; variant?: 'grid' | 'marquee' }) {
    const { isRtl } = useI18n();
    const item = (logo: Partner, key: string, hidden = false) => (
        <li key={key} aria-hidden={hidden || undefined} className="flex h-24 w-44 shrink-0 items-center justify-center rounded-2xl bg-white px-6 ring-1 ring-stone-200/80 sm:w-52">
            {logo.logo ? (
                <img src={logo.logo} alt={hidden ? '' : logo.name} loading="lazy" className="max-h-14 w-auto max-w-full object-contain" />
            ) : (
                <span className="text-center font-semibold text-navy-800">{logo.name}</span>
            )}
        </li>
    );

    if (variant === 'marquee') {
        return (
            <div className="group relative overflow-hidden [mask-image:linear-gradient(to_right,transparent,black_8%,black_92%,transparent)]">
                <ul
                    className="flex w-max gap-4 group-hover:[animation-play-state:paused] motion-reduce:!animate-none motion-reduce:flex-wrap"
                    style={{ animation: `${isRtl ? 'marquee-rtl' : 'marquee'} 45s linear infinite` }}
                >
                    {logos.map((logo) => item(logo, `a-${logo.id}`))}
                    {logos.map((logo) => item(logo, `b-${logo.id}`, true))}
                </ul>
            </div>
        );
    }

    return <ul className="flex flex-wrap justify-center gap-4">{logos.map((logo) => item(logo, String(logo.id)))}</ul>;
}

export function StatsBand({ stats, light = false }: { stats: Stats; light?: boolean }) {
    const { t, number } = useI18n();
    const items = [
        { value: stats.years, suffix: '+', label: t('stats.years') },
        { value: stats.projects, suffix: '+', label: t('stats.projects') },
        { value: stats.sectors, suffix: '', label: t('stats.sectors') },
        { value: stats.partners, suffix: '', label: t('stats.partners') },
    ];
    return (
        <dl className={cn('grid grid-cols-2 gap-px overflow-hidden rounded-3xl lg:grid-cols-4', light ? 'bg-white/10' : 'bg-stone-200')}>
            {items.map((item) => (
                <div key={item.label} className={cn('flex flex-col-reverse gap-2 p-6 sm:p-8', light ? 'bg-navy-900/60 backdrop-blur' : 'bg-white')}>
                    <dt className={cn('text-sm font-semibold', light ? 'text-navy-100/75' : 'text-stone-600')}>{item.label}</dt>
                    <dd className={cn('font-display text-4xl font-semibold sm:text-5xl', light ? 'text-white' : 'text-navy-800')}>
                        {number(item.value)}
                        <span className="text-stone-400">{item.suffix}</span>
                    </dd>
                </div>
            ))}
        </dl>
    );
}

export function ProcessSteps() {
    const { t, list, number, locale } = useI18n();
    const steps = list<{ title: string; text: string }>('process.steps');
    return (
        <section className="bg-white py-24 sm:py-32">
            <div className="container-site">
                <SectionHeader eyebrow={t('process.eyebrow')} title={t('process.title')} />
                <ol className="mt-16 grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
                    {steps.map((step, i) => (
                        <li key={i} data-reveal style={{ '--reveal-delay': `${(i % 3) * 90}ms` } as React.CSSProperties} className="relative border-t-2 border-stone-200 pt-8">
                            <span className="absolute -top-px start-0 h-0.5 w-16 bg-navy-700" aria-hidden="true" />
                            <span className="font-display text-5xl font-semibold text-stone-300">{locale === 'ar' ? number(i + 1) : String(i + 1).padStart(2, '0')}</span>
                            <h3 className="mt-4 text-xl font-semibold text-navy-900">{step.title}</h3>
                            <p className="mt-3 leading-relaxed text-stone-600">{step.text}</p>
                        </li>
                    ))}
                </ol>
            </div>
        </section>
    );
}

const valueIcons = { hse: HardHat, quality: Award, respect: HeartHandshake, trust: ShieldCheck, innovation: Lightbulb } as const;

export function Values({ dark = false }: { dark?: boolean }) {
    const { t } = useI18n();
    const keys = Object.keys(valueIcons) as (keyof typeof valueIcons)[];
    return (
        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
            {keys.map((key, i) => {
                const ValueIcon = valueIcons[key];
                return (
                    <div key={key} data-reveal style={{ '--reveal-delay': `${i * 70}ms` } as React.CSSProperties} className={cn('rounded-3xl p-6', dark ? 'bg-white/[0.05] ring-1 ring-white/10' : 'bg-white ring-1 ring-stone-200')}>
                        <ValueIcon className={cn('size-7', dark ? 'text-stone-200' : 'text-navy-700')} strokeWidth={1.6} aria-hidden="true" />
                        <h3 className={cn('mt-5 text-lg font-semibold', dark ? 'text-white' : 'text-navy-900')}>{t(`values.items.${key}.title`)}</h3>
                        <p className={cn('mt-2 text-[0.95rem] leading-relaxed', dark ? 'text-navy-100/75' : 'text-stone-600')}>{t(`values.items.${key}.text`)}</p>
                    </div>
                );
            })}
        </div>
    );
}

export function Faq() {
    const { t, list } = useI18n();
    const items = list<{ q: string; a: string }>('faq.items');
    const [open, setOpen] = useState<number | null>(0);
    return (
        <section className="bg-stone-100 py-24 sm:py-32">
            <div className="container-site grid gap-12 lg:grid-cols-12">
                <div className="lg:col-span-4">
                    <SectionHeader eyebrow={t('faq.eyebrow')} title={t('faq.title')} />
                </div>
                <div className="divide-y divide-stone-300/70 border-y border-stone-300/70 lg:col-span-8">
                    {items.map((item, i) => {
                        const isOpen = open === i;
                        return (
                            <div key={i}>
                                <h3>
                                    <button
                                        type="button"
                                        onClick={() => setOpen(isOpen ? null : i)}
                                        aria-expanded={isOpen}
                                        aria-controls={`faq-${i}`}
                                        className="flex w-full cursor-pointer items-center justify-between gap-6 py-6 text-start text-lg font-semibold text-navy-900 transition hover:text-navy-600 sm:text-xl"
                                        style={{ fontFamily: 'inherit' }}
                                    >
                                        {item.q}
                                        <span className={cn('inline-flex size-9 shrink-0 items-center justify-center rounded-full transition', isOpen ? 'bg-navy-700 text-white' : 'bg-white text-navy-700 ring-1 ring-stone-300')}>
                                            {isOpen ? <Minus className="size-4" aria-hidden="true" /> : <Plus className="size-4" aria-hidden="true" />}
                                        </span>
                                    </button>
                                </h3>
                                <div id={`faq-${i}`} role="region" hidden={!isOpen} className="pe-14 pb-6 text-lg leading-relaxed text-stone-600">
                                    {item.a}
                                </div>
                            </div>
                        );
                    })}
                </div>
            </div>
        </section>
    );
}

export function CtaBand({ title, text, service }: { title?: string; text?: string; service?: string }) {
    const { site } = usePage<SharedProps>().props;
    const { t, href } = useI18n();
    const wa = whatsappUrl(site.whatsapp, t('common.whatsapp_message'));
    return (
        <section className="relative isolate overflow-hidden bg-navy-800 py-24 text-white sm:py-28">
            <Shards tone="dark" className="absolute inset-x-0 top-0 -z-10 h-24 w-full rotate-180 opacity-60" />
            <div className="mark-mask absolute -end-20 top-1/2 -z-10 size-[30rem] -translate-y-1/2 text-white/[0.05]" aria-hidden="true" />
            <div className="container-site flex flex-col items-start justify-between gap-10 lg:flex-row lg:items-center">
                <div data-reveal className="max-w-2xl">
                    <h2 className="text-3xl leading-tight font-semibold sm:text-4xl lg:text-5xl">{title ?? t('cta.title')}</h2>
                    <p className="mt-5 text-lg leading-relaxed text-navy-100/80">{text ?? t('cta.text')}</p>
                </div>
                <div data-reveal className="flex w-full flex-col gap-3 sm:w-auto sm:flex-row">
                    <Link href={service ? `${href('/contact')}?service=${service}` : href('/contact')} className={buttonClass('light')}>
                        {t('cta.primary')}
                        <Arrow />
                    </Link>
                    {wa && (
                        <a href={wa} target="_blank" rel="noopener noreferrer" className={buttonClass('outline-light')}>
                            <WhatsAppIcon className="size-5" />
                            {t('cta.secondary')}
                        </a>
                    )}
                </div>
            </div>
        </section>
    );
}
