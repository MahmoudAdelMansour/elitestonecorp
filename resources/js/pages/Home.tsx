import { ArrowDown, Check } from 'lucide-react';
import { CtaBand, Faq, LogoWall, ProcessSteps, ProjectCard, SectorGrid, ServiceCard, StatsBand, Values } from '@/components/sections';
import { ButtonLink, SectionHeader, Shards } from '@/components/ui';
import { useI18n } from '@/lib/i18n';
import type { Partner, Project, Sector, Service, Stats } from '@/types';

interface Props {
    heroImage: string | null;
    aboutImage: string | null;
    services: Service[];
    sectors: Sector[];
    projects: Project[];
    clients: Partner[];
    partners: Partner[];
    stats: Stats;
}

export default function Home({ heroImage, aboutImage, services, sectors, projects, clients, partners, stats }: Props) {
    const { t, list, href } = useI18n();
    const systems = services.filter((s) => s.group === 'systems');
    const contracting = services.filter((s) => s.group === 'contracting');
    const why = list<{ title: string; text: string }>('why.items');

    return (
        <>
            {/* Hero */}
            <section className="relative isolate flex min-h-[100svh] flex-col overflow-hidden bg-navy-950 text-white">
                {heroImage && <img src={heroImage} alt="" fetchPriority="high" className="absolute inset-0 -z-20 size-full object-cover" />}
                <div className="absolute inset-0 -z-10 bg-gradient-to-r from-navy-950 via-navy-950/85 to-navy-900/30 rtl:bg-gradient-to-l" />
                <div className="absolute inset-0 -z-10 bg-gradient-to-t from-navy-950/80 via-transparent to-navy-950/40" />
                <div className="container-site flex flex-1 flex-col justify-center pt-32 pb-16">
                    <div className="max-w-3xl">
                        <p data-reveal className="eyebrow text-stone-300">
                            {t('hero.eyebrow')}
                        </p>
                        <h1 data-reveal style={{ '--reveal-delay': '80ms' } as React.CSSProperties} className="mt-6 text-[2.6rem] leading-[1.06] font-semibold sm:text-6xl lg:text-7xl">
                            {t('hero.title')}
                        </h1>
                        <p data-reveal style={{ '--reveal-delay': '160ms' } as React.CSSProperties} className="mt-7 max-w-2xl text-lg leading-relaxed text-navy-100/85 sm:text-xl">
                            {t('hero.subtitle')}
                        </p>
                        <div data-reveal style={{ '--reveal-delay': '240ms' } as React.CSSProperties} className="mt-10 flex flex-col gap-3 sm:flex-row">
                            <ButtonLink href={href('/contact')} variant="light" arrow>
                                {t('hero.primary')}
                            </ButtonLink>
                            <ButtonLink href={href('/projects')} variant="outline-light">
                                {t('hero.secondary')}
                            </ButtonLink>
                        </div>
                    </div>
                </div>
                <div className="container-site relative z-10 pb-24 sm:pb-32">
                    <StatsBand stats={stats} light />
                </div>
                <a href="#clients" className="absolute start-1/2 bottom-28 hidden -translate-x-1/2 flex-col items-center gap-2 text-xs font-semibold tracking-widest text-white/60 uppercase transition hover:text-white xl:flex rtl:translate-x-1/2">
                    <ArrowDown className="size-4 animate-bounce" aria-hidden="true" />
                    <span className="sr-only">{t('hero.scroll')}</span>
                </a>
                <Shards className="absolute inset-x-0 bottom-0 h-20 w-full sm:h-28 rtl:-scale-x-100" />
            </section>

            {/* Clients */}
            {clients.length > 0 && (
                <section id="clients" className="bg-stone-50 pt-10 pb-20">
                    <div className="container-site">
                        <SectionHeader eyebrow={t('clients.eyebrow')} title={t('clients.title')} align="center" className="[&_h2]:text-2xl [&_h2]:sm:text-3xl" />
                    </div>
                    <div className="mt-12">
                        <LogoWall logos={clients} variant="marquee" />
                    </div>
                </section>
            )}

            {/* About */}
            <section className="bg-stone-50 pb-24 sm:pb-32">
                <div className="container-site grid items-center gap-14 lg:grid-cols-2">
                    <div data-reveal className="relative">
                        <div className="aspect-[4/5] overflow-hidden rounded-[2rem] bg-stone-200 sm:aspect-[5/5]">
                            {aboutImage && <img src={aboutImage} alt="" loading="lazy" className="size-full object-cover" />}
                        </div>
                        <div className="absolute -end-4 -bottom-8 max-w-[15rem] rounded-3xl bg-navy-800 p-6 text-white shadow-2xl sm:-end-8">
                            <span className="mark-mask block size-10 text-stone-200" aria-hidden="true" />
                            <p className="mt-4 font-display text-2xl leading-snug font-semibold">{t('tagline')}</p>
                            <p className="mt-2 text-sm text-navy-100/75">{t('common.since', { year: 2019 })}</p>
                        </div>
                    </div>
                    <div>
                        <SectionHeader eyebrow={t('about.eyebrow')} title={t('about.title')} />
                        <p data-reveal className="mt-6 text-lg leading-relaxed text-stone-600">
                            {t('about.body')}
                        </p>
                        <ul data-reveal className="mt-8 grid gap-3 sm:grid-cols-2">
                            {(['hse', 'quality', 'trust', 'innovation'] as const).map((key) => (
                                <li key={key} className="flex items-center gap-3 font-semibold text-navy-900">
                                    <span className="inline-flex size-7 items-center justify-center rounded-full bg-navy-100 text-navy-700">
                                        <Check className="size-4" aria-hidden="true" />
                                    </span>
                                    {t(`values.items.${key}.title`)}
                                </li>
                            ))}
                        </ul>
                        <div data-reveal className="mt-10">
                            <ButtonLink href={href('/about')} variant="secondary" arrow>
                                {t('about.cta')}
                            </ButtonLink>
                        </div>
                    </div>
                </div>
            </section>

            {/* Services */}
            <section className="bg-white py-24 sm:py-32">
                <div className="container-site">
                    <div className="flex flex-col justify-between gap-8 lg:flex-row lg:items-end">
                        <SectionHeader eyebrow={t('services.eyebrow')} title={t('services.title')} subtitle={t('services.subtitle')} />
                        <ButtonLink href={href('/services')} variant="secondary" arrow className="shrink-0 self-start lg:self-end">
                            {t('common.view_all_services')}
                        </ButtonLink>
                    </div>

                    <h3 data-reveal className="mt-16 flex items-center gap-4 font-sans text-sm font-bold tracking-widest text-stone-500 uppercase rtl:tracking-normal">
                        {t('services.systems')}
                        <span className="h-px flex-1 bg-stone-200" aria-hidden="true" />
                    </h3>
                    <div className="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        {systems.map((service, i) => (
                            <ServiceCard key={service.id} service={service} index={i} />
                        ))}
                    </div>

                    <h3 data-reveal className="mt-20 flex items-center gap-4 font-sans text-sm font-bold tracking-widest text-stone-500 uppercase rtl:tracking-normal">
                        {t('services.contracting')}
                        <span className="h-px flex-1 bg-stone-200" aria-hidden="true" />
                    </h3>
                    <div className="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        {contracting.map((service, i) => (
                            <ServiceCard key={service.id} service={service} index={i} />
                        ))}
                    </div>
                </div>
            </section>

            {/* Sectors */}
            <section className="relative isolate overflow-hidden bg-navy-900 py-24 sm:py-32">
                <div className="mark-mask absolute -start-32 top-10 -z-10 size-[36rem] text-white/[0.03]" aria-hidden="true" />
                <div className="container-site">
                    <SectionHeader eyebrow={t('sectors.eyebrow')} title={t('sectors.title')} subtitle={t('sectors.subtitle')} light />
                    <div className="mt-14">
                        <SectorGrid sectors={sectors} />
                    </div>
                </div>
            </section>

            {/* Featured projects */}
            {projects.length > 0 && (
                <section className="bg-stone-50 py-24 sm:py-32">
                    <div className="container-site">
                        <div className="flex flex-col justify-between gap-8 lg:flex-row lg:items-end">
                            <SectionHeader eyebrow={t('projects.eyebrow')} title={t('projects.title')} />
                            <ButtonLink href={href('/projects')} variant="secondary" arrow className="shrink-0 self-start lg:self-end">
                                {t('common.view_all_projects')}
                            </ButtonLink>
                        </div>
                        <div className="mt-14 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            {projects.map((project, i) => (
                                <div key={project.id} className={i === 0 ? 'md:col-span-2 lg:row-span-2' : undefined}>
                                    <ProjectCard project={project} index={i} tall={i === 0} />
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
            )}

            <ProcessSteps />

            {/* Why us + partners */}
            <section className="bg-stone-50 py-24 sm:py-32">
                <div className="container-site">
                    <SectionHeader eyebrow={t('why.eyebrow')} title={t('why.title')} />
                    <div className="mt-14 grid gap-6 md:grid-cols-2">
                        {why.map((item, i) => (
                            <div key={i} data-reveal style={{ '--reveal-delay': `${(i % 2) * 90}ms` } as React.CSSProperties} className="flex gap-5 rounded-3xl bg-white p-7 ring-1 ring-stone-200">
                                <span className="font-display text-3xl font-semibold text-stone-300">0{i + 1}</span>
                                <div>
                                    <h3 className="text-xl font-semibold text-navy-900">{item.title}</h3>
                                    <p className="mt-2 leading-relaxed text-stone-600">{item.text}</p>
                                </div>
                            </div>
                        ))}
                    </div>
                    {partners.length > 0 && (
                        <div className="mt-20">
                            <h3 data-reveal className="text-center font-sans text-sm font-bold tracking-widest text-stone-500 uppercase rtl:tracking-normal">
                                {t('why.partners_title')}
                            </h3>
                            <div data-reveal className="mt-8">
                                <LogoWall logos={partners} />
                            </div>
                        </div>
                    )}
                </div>
            </section>

            {/* Values */}
            <section className="bg-navy-950 py-24 sm:py-28">
                <div className="container-site">
                    <SectionHeader eyebrow={t('values.eyebrow')} title={t('values.title')} light />
                    <div className="mt-12">
                        <Values dark />
                    </div>
                </div>
            </section>

            <Faq />
            <CtaBand />
        </>
    );
}
