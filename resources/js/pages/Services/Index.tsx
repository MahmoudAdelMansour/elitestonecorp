import { CtaBand, ProcessSteps, ServiceCard } from '@/components/sections';
import { PageHero, SectionHeader } from '@/components/ui';
import { useI18n } from '@/lib/i18n';
import type { Service } from '@/types';

export default function ServicesIndex({ services }: { services: Service[] }) {
    const { t } = useI18n();
    const groups = [
        { key: 'systems', title: t('services.systems'), text: t('services.systems_text') },
        { key: 'contracting', title: t('services.contracting'), text: t('services.contracting_text') },
    ] as const;

    return (
        <>
            <PageHero title={t('services.page_title')} subtitle={t('services.page_subtitle')} image={services[0]?.image} eyebrow={t('services.eyebrow')} crumbs={[{ label: t('nav.services') }]} />
            {groups.map((group, g) => (
                <section key={group.key} className={g === 0 ? 'bg-stone-50 py-24 sm:py-28' : 'bg-white py-24 sm:py-28'}>
                    <div className="container-site">
                        <SectionHeader title={group.title} subtitle={group.text} />
                        <div className="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            {services
                                .filter((s) => s.group === group.key)
                                .map((service, i) => (
                                    <ServiceCard key={service.id} service={service} index={i} />
                                ))}
                        </div>
                    </div>
                </section>
            ))}
            <ProcessSteps />
            <CtaBand />
        </>
    );
}
