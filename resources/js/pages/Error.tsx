import { ButtonLink, Shards } from '@/components/ui';
import { useI18n } from '@/lib/i18n';

export default function Error({ status }: { status: number }) {
    const { t, href } = useI18n();
    return (
        <section className="relative isolate flex min-h-[80svh] items-center overflow-hidden bg-navy-900 pt-32 pb-28 text-white">
            <div className="mark-mask absolute -end-20 top-1/2 -z-10 size-[34rem] -translate-y-1/2 text-white/[0.05]" aria-hidden="true" />
            <div className="container-site">
                <p className="font-display text-8xl font-semibold text-stone-300/60">{status}</p>
                <h1 className="mt-4 text-4xl font-semibold sm:text-5xl">{t('errors.not_found_title')}</h1>
                <p className="mt-5 max-w-xl text-lg text-navy-100/80">{t('errors.not_found_text')}</p>
                <ButtonLink href={href('/')} variant="light" arrow className="mt-10">
                    {t('errors.go_home')}
                </ButtonLink>
            </div>
            <Shards className="absolute inset-x-0 bottom-0 h-20 w-full rtl:-scale-x-100" />
        </section>
    );
}
