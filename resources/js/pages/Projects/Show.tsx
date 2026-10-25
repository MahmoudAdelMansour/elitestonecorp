import { Link } from '@inertiajs/react';
import { Building, Calendar, Layers, MapPin, Ruler } from 'lucide-react';
import { CtaBand, ProjectCard } from '@/components/sections';
import { PageHero, SectionHeader } from '@/components/ui';
import { useI18n } from '@/lib/i18n';
import type { Project } from '@/types';

interface Props {
    project: Project & { challenge: string | null; solution: string | null; gallery: string[] };
    related: Project[];
}

export default function ProjectShow({ project, related }: Props) {
    const { t, href } = useI18n();

    const facts = [
        { icon: Building, label: t('common.client'), value: project.client },
        { icon: Calendar, label: t('common.year'), value: project.year },
        { icon: MapPin, label: t('common.location'), value: project.location },
        { icon: Layers, label: t('common.sector'), value: project.sector?.name },
        { icon: Ruler, label: t('common.area'), value: project.area },
    ].filter((f) => f.value);

    const images = [project.cover, ...project.gallery].filter(Boolean) as string[];

    return (
        <>
            <PageHero title={project.title} subtitle={project.summary} image={project.cover} eyebrow={project.sector?.name} crumbs={[{ label: t('nav.projects'), href: href('/projects') }, { label: project.title }]} />

            <section className="bg-stone-50 py-20 sm:py-24">
                <div className="container-site grid gap-14 lg:grid-cols-12">
                    <aside className="lg:col-span-4">
                        <div data-reveal className="sticky top-28 rounded-[2rem] bg-white p-8 ring-1 ring-stone-200">
                            <h2 className="text-xl font-semibold text-navy-900">{t('projects.overview')}</h2>
                            <dl className="mt-6 space-y-5">
                                {facts.map(({ icon: FactIcon, label, value }) => (
                                    <div key={label} className="flex gap-4">
                                        <FactIcon className="mt-0.5 size-5 shrink-0 text-navy-500" aria-hidden="true" strokeWidth={1.7} />
                                        <div>
                                            <dt className="text-sm text-stone-500">{label}</dt>
                                            <dd className="font-semibold text-navy-900">{value}</dd>
                                        </div>
                                    </div>
                                ))}
                            </dl>
                            {project.services && project.services.length > 0 && (
                                <div className="mt-8 border-t border-stone-200 pt-6">
                                    <p className="text-sm text-stone-500">{t('common.scope')}</p>
                                    <ul className="mt-3 flex flex-wrap gap-2">
                                        {project.services.map((service) => (
                                            <li key={service.slug}>
                                                <Link href={href(`/services/${service.slug}`)} className="inline-flex rounded-full bg-navy-50 px-3 py-1.5 text-sm font-semibold text-navy-700 transition hover:bg-navy-100">
                                                    {service.title}
                                                </Link>
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            )}
                        </div>
                    </aside>

                    <div className="space-y-12 lg:col-span-8">
                        {project.challenge && (
                            <div data-reveal>
                                <p className="eyebrow">{t('projects.challenge')}</p>
                                <p className="mt-4 text-xl leading-relaxed text-navy-900">{project.challenge}</p>
                            </div>
                        )}
                        {project.solution && (
                            <div data-reveal>
                                <p className="eyebrow">{t('projects.solution')}</p>
                                <p className="mt-4 text-xl leading-relaxed text-navy-900">{project.solution}</p>
                            </div>
                        )}
                        {images.length > 0 && (
                            <div data-reveal>
                                <h2 className="sr-only">{t('projects.gallery')}</h2>
                                <div className="grid gap-4 sm:grid-cols-2">
                                    {images.map((src, i) => (
                                        <a key={src + i} href={src} target="_blank" rel="noopener" className={i === 0 ? 'overflow-hidden rounded-3xl sm:col-span-2' : 'overflow-hidden rounded-3xl'}>
                                            <img src={src} alt={`${project.title} — ${i + 1}`} loading="lazy" className="aspect-[16/10] size-full object-cover transition duration-500 hover:scale-105" />
                                        </a>
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </section>

            {related.length > 0 && (
                <section className="bg-white py-24">
                    <div className="container-site">
                        <SectionHeader title={t('projects.related')} />
                        <div className="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            {related.map((item, i) => (
                                <ProjectCard key={item.id} project={item} index={i} />
                            ))}
                        </div>
                    </div>
                </section>
            )}

            <CtaBand title={t('projects.similar_cta')} />
        </>
    );
}
