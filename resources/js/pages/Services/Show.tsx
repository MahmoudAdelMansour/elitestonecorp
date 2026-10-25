import { Link } from '@inertiajs/react';
import { Check } from 'lucide-react';
import Icon from '@/components/Icon';
import { CtaBand, ProjectCard } from '@/components/sections';
import { Arrow, ButtonLink, PageHero, SectionHeader } from '@/components/ui';
import { useI18n } from '@/lib/i18n';
import type { Project, Service } from '@/types';

interface Props {
    service: Service & { body: string | null; features: string[] };
    projects: Project[];
    others: Service[];
}

export default function ServiceShow({ service, projects, others }: Props) {
    const { t, href } = useI18n();

    return (
        <>
            <PageHero title={service.title} subtitle={service.excerpt} image={service.image} eyebrow={service.group === 'systems' ? t('services.systems') : t('services.contracting')} crumbs={[{ label: t('nav.services'), href: href('/services') }, { label: service.title }]}>
                <ButtonLink href={`${href('/contact')}?service=${service.slug}`} variant="light" arrow className="mt-10">
                    {t('services.quote_for')}
                </ButtonLink>
            </PageHero>

            <section className="bg-stone-50 py-24 sm:py-28">
                <div className="container-site grid gap-14 lg:grid-cols-12">
                    <div data-reveal className="lg:col-span-7">
                        <span className="inline-flex size-14 items-center justify-center rounded-2xl bg-navy-700 text-white">
                            <Icon name={service.icon} className="size-7" />
                        </span>
                        <div className="prose-site mt-8 text-lg leading-relaxed text-stone-700">
                            {(service.body ?? '').split(/\n{2,}/).map((p, i) => (
                                <p key={i} className={i === 0 ? 'text-xl leading-relaxed text-navy-900' : ''}>
                                    {p}
                                </p>
                            ))}
                        </div>
                        {service.image && (
                            <div className="mt-12 aspect-[16/9] overflow-hidden rounded-[2rem] bg-stone-200">
                                <img src={service.image} alt={service.title} loading="lazy" className="size-full object-cover" />
                            </div>
                        )}
                    </div>
                    <aside className="lg:col-span-5">
                        <div className="sticky top-28 space-y-6">
                            {service.features.length > 0 && (
                                <div data-reveal className="rounded-[2rem] bg-white p-8 ring-1 ring-stone-200">
                                    <h2 className="text-2xl font-semibold text-navy-900">{t('services.features')}</h2>
                                    <ul className="mt-6 space-y-4">
                                        {service.features.map((feature) => (
                                            <li key={feature} className="flex gap-3 text-stone-700">
                                                <span className="mt-0.5 inline-flex size-6 shrink-0 items-center justify-center rounded-full bg-navy-100 text-navy-700">
                                                    <Check className="size-3.5" aria-hidden="true" />
                                                </span>
                                                {feature}
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            )}
                            <div data-reveal className="rounded-[2rem] bg-navy-800 p-8 text-white">
                                <h2 className="text-2xl font-semibold">{t('services.quote_for')}</h2>
                                <p className="mt-3 leading-relaxed text-navy-100/80">{t('services.quote_text')}</p>
                                <ButtonLink href={`${href('/contact')}?service=${service.slug}`} variant="light" arrow className="mt-6 w-full">
                                    {t('nav.quote')}
                                </ButtonLink>
                            </div>
                        </div>
                    </aside>
                </div>
            </section>

            {projects.length > 0 && (
                <section className="bg-white py-24 sm:py-28">
                    <div className="container-site">
                        <SectionHeader title={t('services.related_projects')} />
                        <div className="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            {projects.map((project, i) => (
                                <ProjectCard key={project.id} project={project} index={i} />
                            ))}
                        </div>
                    </div>
                </section>
            )}

            <section className="bg-stone-100 py-20">
                <div className="container-site">
                    <h2 className="text-2xl font-semibold text-navy-900">{t('services.other_services')}</h2>
                    <ul className="mt-8 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        {others.map((other) => (
                            <li key={other.id}>
                                <Link href={href(`/services/${other.slug}`)} className="group flex h-full items-center gap-4 rounded-2xl bg-white p-5 ring-1 ring-stone-200 transition hover:ring-navy-300">
                                    <Icon name={other.icon} className="size-6 shrink-0 text-navy-700" />
                                    <span className="flex-1 font-semibold text-navy-900">{other.title}</span>
                                    <Arrow className="text-navy-500" />
                                </Link>
                            </li>
                        ))}
                    </ul>
                </div>
            </section>

            <CtaBand service={service.slug} />
        </>
    );
}
