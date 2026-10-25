import { Link } from '@inertiajs/react';
import { CtaBand, ProjectCard } from '@/components/sections';
import { PageHero } from '@/components/ui';
import { cn } from '@/lib/cn';
import { useI18n } from '@/lib/i18n';
import type { Project, Sector, Service } from '@/types';

interface Props {
    projects: Project[];
    sectors: Sector[];
    services: Service[];
    filters: { sector: string | null; service: string | null };
}

function Chip({ active, href, children }: { active: boolean; href: string; children: React.ReactNode }) {
    return (
        <Link
            href={href}
            preserveScroll
            preserveState
            only={['projects', 'filters']}
            aria-current={active ? 'true' : undefined}
            className={cn(
                'inline-flex min-h-10 items-center rounded-full px-4 text-sm font-semibold whitespace-nowrap transition',
                active ? 'bg-navy-700 text-white shadow-md shadow-navy-900/20' : 'bg-white text-navy-800 ring-1 ring-stone-200 hover:ring-navy-300',
            )}
        >
            {children}
        </Link>
    );
}

export default function ProjectsIndex({ projects, sectors, services, filters }: Props) {
    const { t, href, tc } = useI18n();

    const url = (next: Partial<Props['filters']>) => {
        const params = new URLSearchParams();
        const merged = { ...filters, ...next };
        if (merged.sector) params.set('sector', merged.sector);
        if (merged.service) params.set('service', merged.service);
        const query = params.toString();
        return href('/projects') + (query ? `?${query}` : '');
    };

    return (
        <>
            <PageHero title={t('projects.page_title')} subtitle={t('projects.page_subtitle')} image={projects[0]?.cover} eyebrow={t('projects.eyebrow')} crumbs={[{ label: t('nav.projects') }]} />

            <section className="bg-stone-50 py-16 sm:py-20">
                <div className="container-site">
                    <div className="space-y-5">
                        <div role="group" aria-label={t('projects.filter_sector')}>
                            <p className="mb-3 text-sm font-bold text-stone-500">{t('projects.filter_sector')}</p>
                            <div className="-mx-4 flex gap-2 overflow-x-auto px-4 pb-1 sm:mx-0 sm:flex-wrap sm:px-0">
                                <Chip active={!filters.sector} href={url({ sector: null })}>
                                    {t('common.all')}
                                </Chip>
                                {sectors.map((sector) => (
                                    <Chip key={sector.id} active={filters.sector === sector.slug} href={url({ sector: sector.slug })}>
                                        {sector.name}
                                    </Chip>
                                ))}
                            </div>
                        </div>
                        <div role="group" aria-label={t('projects.filter_service')}>
                            <p className="mb-3 text-sm font-bold text-stone-500">{t('projects.filter_service')}</p>
                            <div className="-mx-4 flex gap-2 overflow-x-auto px-4 pb-1 sm:mx-0 sm:flex-wrap sm:px-0">
                                <Chip active={!filters.service} href={url({ service: null })}>
                                    {t('common.all')}
                                </Chip>
                                {services.map((service) => (
                                    <Chip key={service.id} active={filters.service === service.slug} href={url({ service: service.slug })}>
                                        {service.title}
                                    </Chip>
                                ))}
                            </div>
                        </div>
                    </div>

                    <p className="mt-10 text-sm font-semibold text-stone-500" aria-live="polite">
                        {tc('common.projects_count', projects.length)}
                    </p>

                    {projects.length > 0 ? (
                        <div className="mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            {projects.map((project, i) => (
                                <ProjectCard key={project.id} project={project} index={i} />
                            ))}
                        </div>
                    ) : (
                        <div className="mt-6 rounded-3xl border border-dashed border-stone-300 bg-white p-12 text-center">
                            <p className="text-lg text-stone-600">{t('projects.empty')}</p>
                            <Link href={href('/projects')} className="mt-4 inline-block font-semibold text-navy-700 underline underline-offset-4">
                                {t('projects.reset')}
                            </Link>
                        </div>
                    )}
                </div>
            </section>

            <CtaBand />
        </>
    );
}
