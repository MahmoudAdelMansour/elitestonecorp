import { Download } from 'lucide-react';
import { usePage } from '@inertiajs/react';
import { CtaBand, LogoWall, StatsBand, Values } from '@/components/sections';
import { buttonClass, PageHero, SectionHeader } from '@/components/ui';
import { useI18n } from '@/lib/i18n';
import type { Partner, SharedProps, Stats } from '@/types';

interface Props {
    image: string | null;
    gallery: string[];
    clients: Partner[];
    partners: Partner[];
    stats: Stats;
}

export default function About({ image, gallery, clients, partners, stats }: Props) {
    const { site } = usePage<SharedProps>().props;
    const { t, list } = useI18n();
    const story = list<string>('about.story');
    const timeline = list<{ year: string; text: string }>('about.timeline');

    return (
        <>
            <PageHero title={t('about.page_title')} subtitle={t('about.page_subtitle')} image={image} eyebrow={t('common.since', { year: site.foundedYear })} crumbs={[{ label: t('nav.about') }]}>
                {site.profileUrl && (
                    <a href={site.profileUrl} className={`${buttonClass('light')} mt-10`}>
                        <Download className="size-4" aria-hidden="true" />
                        {t('common.download_profile')}
                    </a>
                )}
            </PageHero>

            <section className="bg-stone-50 py-24 sm:py-32">
                <div className="container-site grid gap-14 lg:grid-cols-12">
                    <div className="lg:col-span-5">
                        <SectionHeader eyebrow={t('about.eyebrow')} title={t('about.story_title')} />
                        <div data-reveal className="mt-10 grid grid-cols-2 gap-4">
                            {gallery.slice(0, 3).map((src, i) => (
                                <div key={src} className={i === 0 ? 'col-span-2 aspect-[16/10] overflow-hidden rounded-3xl' : 'aspect-square overflow-hidden rounded-3xl'}>
                                    <img src={src} alt="" loading="lazy" className="size-full object-cover" />
                                </div>
                            ))}
                        </div>
                    </div>
                    <div data-reveal className="prose-site text-lg leading-relaxed text-stone-700 lg:col-span-7 lg:pt-16">
                        {story.map((p, i) => (
                            <p key={i} className={i === 0 ? 'text-2xl leading-snug font-medium text-navy-900' : ''}>
                                {p}
                            </p>
                        ))}
                        <div className="mt-12">
                            <StatsBand stats={stats} />
                        </div>
                    </div>
                </div>
            </section>

            <section className="bg-white py-24 sm:py-32">
                <div className="container-site grid gap-6 lg:grid-cols-2">
                    <article data-reveal className="relative overflow-hidden rounded-[2rem] bg-navy-800 p-10 text-white sm:p-14">
                        <div className="mark-mask absolute -end-10 -bottom-10 size-64 text-white/[0.05]" aria-hidden="true" />
                        <p className="eyebrow text-stone-300">{t('about.vision_title')}</p>
                        <h2 className="mt-5 text-3xl font-semibold sm:text-4xl">{t('about.vision_lead')}</h2>
                        <p className="mt-6 text-lg leading-relaxed text-navy-100/85">{t('about.vision')}</p>
                    </article>
                    <article data-reveal style={{ '--reveal-delay': '100ms' } as React.CSSProperties} className="rounded-[2rem] bg-stone-200 p-10 sm:p-14">
                        <p className="eyebrow">{t('about.mission_title')}</p>
                        <h2 className="mt-5 text-3xl font-semibold text-navy-900 sm:text-4xl">{t('tagline')}</h2>
                        <p className="mt-6 text-lg leading-relaxed text-stone-700">{t('about.mission')}</p>
                    </article>
                </div>
            </section>

            <section className="bg-stone-50 py-24 sm:py-32">
                <div className="container-site">
                    <SectionHeader eyebrow={t('values.eyebrow')} title={t('values.title')} />
                    <div className="mt-12">
                        <Values />
                    </div>
                </div>
            </section>

            <section className="bg-white py-24 sm:py-32">
                <div className="container-site grid gap-14 lg:grid-cols-12">
                    <div className="lg:col-span-4">
                        <SectionHeader title={t('about.timeline_title')} />
                    </div>
                    <ol className="relative border-s-2 border-stone-200 lg:col-span-8">
                        {timeline.map((item, i) => (
                            <li key={item.year} data-reveal style={{ '--reveal-delay': `${i * 60}ms` } as React.CSSProperties} className="relative ps-10 pb-12 last:pb-0">
                                <span className="absolute -start-[9px] top-2 size-4 rounded-full border-4 border-white bg-navy-700 ring-2 ring-navy-700" aria-hidden="true" />
                                <p className="font-display text-3xl font-semibold text-navy-700">{item.year}</p>
                                <p className="mt-2 max-w-xl text-lg leading-relaxed text-stone-600">{item.text}</p>
                            </li>
                        ))}
                    </ol>
                </div>
            </section>

            <section className="bg-stone-100 py-24 sm:py-28">
                <div className="container-site space-y-20">
                    {clients.length > 0 && (
                        <div>
                            <SectionHeader eyebrow={t('clients.eyebrow')} title={t('clients.title')} align="center" className="[&_h2]:text-2xl [&_h2]:sm:text-3xl" />
                            <div data-reveal className="mt-10">
                                <LogoWall logos={clients} />
                            </div>
                        </div>
                    )}
                    {partners.length > 0 && (
                        <div>
                            <SectionHeader eyebrow={t('why.partners_title')} title={t('why.items.1.text')} align="center" className="[&_h2]:text-2xl [&_h2]:sm:text-3xl" />
                            <div data-reveal className="mt-10">
                                <LogoWall logos={partners} />
                            </div>
                        </div>
                    )}
                </div>
            </section>

            <CtaBand />
        </>
    );
}
