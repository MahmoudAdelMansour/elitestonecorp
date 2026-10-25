import { useForm, usePage } from '@inertiajs/react';
import { CircleCheck, Clock, Mail, MapPin, Phone, Send } from 'lucide-react';
import { type FormEvent, type ReactNode, useEffect, useRef } from 'react';
import { WhatsAppIcon } from '@/components/Icon';
import { whatsappUrl } from '@/components/Layout';
import { buttonClass, PageHero } from '@/components/ui';
import { cn } from '@/lib/cn';
import { useI18n } from '@/lib/i18n';
import type { Sector, Service, SharedProps } from '@/types';

interface Props {
    services: Service[];
    sectors: Sector[];
    preselect: { service: string | null };
    timelines: string[];
}

const inputClass =
    'block w-full min-h-12 rounded-xl border-0 bg-white px-4 py-3 text-base text-ink ring-1 ring-stone-300 transition placeholder:text-stone-400 focus:ring-2 focus:ring-navy-600 focus:outline-none aria-[invalid=true]:ring-2 aria-[invalid=true]:ring-red-600';

function Field({ id, label, error, hint, required, optionalLabel, className, children }: { id: string; label: string; error?: string; hint?: string; required?: boolean; optionalLabel: string; className?: string; children: ReactNode }) {
    return (
        <div className={className}>
            <label htmlFor={id} className="mb-2 flex items-baseline justify-between gap-2 text-sm font-semibold text-navy-900">
                <span>
                    {label}
                    {required && (
                        <span className="text-red-600" aria-hidden="true">
                            {' '}*
                        </span>
                    )}
                </span>
                {!required && <span className="text-xs font-normal text-stone-500">{optionalLabel}</span>}
            </label>
            {children}
            {hint && !error && (
                <p id={`${id}-hint`} className="mt-2 text-sm text-stone-500">
                    {hint}
                </p>
            )}
            {error && (
                <p id={`${id}-error`} className="mt-2 text-sm font-medium text-red-700">
                    {error}
                </p>
            )}
        </div>
    );
}

export default function Contact({ services, sectors, preselect, timelines }: Props) {
    const page = usePage<SharedProps>();
    const { site } = page.props;
    const { t, href } = useI18n();
    const summaryRef = useRef<HTMLDivElement>(null);
    const successRef = useRef<HTMLDivElement>(null);
    const reference = page.flash?.inquiry?.reference;

    const preselected = services.find((s) => s.slug === preselect.service);
    const form = useForm({
        name: '',
        company: '',
        job_title: '',
        email: '',
        phone: '',
        service_id: preselected ? String(preselected.id) : '',
        sector_id: '',
        location: '',
        area: '',
        timeline: '',
        message: '',
        website: '',
    });

    const errors = form.errors as Record<string, string | undefined>;
    const hasErrors = Object.keys(form.errors).length > 0;

    const submit = (e: FormEvent) => {
        e.preventDefault();
        form.post(href('/contact'), {
            preserveScroll: true,
            onSuccess: () => form.reset(),
            onError: () => requestAnimationFrame(() => summaryRef.current?.focus()),
        });
    };

    useEffect(() => {
        if (!reference) return;
        successRef.current?.focus({ preventScroll: true });
        successRef.current?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }, [reference]);

    const a11y = (name: string, hint = false) => ({
        id: name,
        name,
        'aria-invalid': errors[name] ? true : undefined,
        'aria-describedby': errors[name] ? `${name}-error` : hint ? `${name}-hint` : undefined,
    });

    const wa = whatsappUrl(site.whatsapp, t('common.whatsapp_message'));
    const optional = t('contact.optional');

    return (
        <>
            <PageHero title={t('contact.page_title')} subtitle={t('contact.page_subtitle')} crumbs={[{ label: t('nav.contact') }]} />

            <section className="bg-stone-50 pb-24 sm:pb-32">
                <div className="container-site -mt-16 grid items-start gap-8 sm:-mt-20 lg:grid-cols-12">
                    <div className="relative z-10 rounded-[2rem] bg-white p-6 shadow-[0_30px_80px_-40px_rgba(18,26,46,0.45)] ring-1 ring-stone-200 sm:p-10 lg:col-span-8">
                        {reference ? (
                            <div ref={successRef} tabIndex={-1} role="status" className="flex flex-col items-start py-10 outline-none">
                                <span className="inline-flex size-16 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">
                                    <CircleCheck className="size-9" aria-hidden="true" />
                                </span>
                                <h2 className="mt-6 text-3xl font-semibold text-navy-900">{t('contact.success_title')}</h2>
                                <p className="mt-4 max-w-lg text-lg leading-relaxed text-stone-600">{t('contact.success_text', { ref: reference })}</p>
                                <button type="button" onClick={() => window.location.assign(href('/contact'))} className={cn(buttonClass('secondary'), 'mt-8')}>
                                    {t('contact.send_another')}
                                </button>
                            </div>
                        ) : (
                            <form onSubmit={submit} noValidate>
                                {hasErrors && (
                                    <div ref={summaryRef} tabIndex={-1} role="alert" className="mb-8 rounded-2xl bg-red-50 p-4 font-semibold text-red-800 ring-1 ring-red-200 outline-none">
                                        {t('contact.error_summary')}
                                    </div>
                                )}

                                <fieldset>
                                    <legend className="font-display text-2xl font-semibold text-navy-900">{t('contact.details_title')}</legend>
                                    <div className="mt-6 grid gap-5 sm:grid-cols-2">
                                        <Field id="name" label={t('contact.name')} error={errors.name} required optionalLabel={optional}>
                                            <input {...a11y('name')} autoComplete="name" required value={form.data.name} onChange={(e) => form.setData('name', e.target.value)} className={inputClass} />
                                        </Field>
                                        <Field id="company" label={t('contact.company')} error={errors.company} optionalLabel={optional}>
                                            <input {...a11y('company')} autoComplete="organization" value={form.data.company} onChange={(e) => form.setData('company', e.target.value)} className={inputClass} />
                                        </Field>
                                        <Field id="phone" label={t('contact.phone')} error={errors.phone} required optionalLabel={optional}>
                                            <input {...a11y('phone')} type="tel" dir="ltr" autoComplete="tel" inputMode="tel" required value={form.data.phone} onChange={(e) => form.setData('phone', e.target.value)} className={cn(inputClass, 'rtl:text-end')} placeholder="+20 1xx xxx xxxx" />
                                        </Field>
                                        <Field id="email" label={t('contact.email')} error={errors.email} required optionalLabel={optional}>
                                            <input {...a11y('email')} type="email" dir="ltr" autoComplete="email" required value={form.data.email} onChange={(e) => form.setData('email', e.target.value)} className={cn(inputClass, 'rtl:text-end')} />
                                        </Field>
                                        <Field id="job_title" label={t('contact.job_title')} error={errors.job_title} optionalLabel={optional} className="sm:col-span-2">
                                            <input {...a11y('job_title')} autoComplete="organization-title" value={form.data.job_title} onChange={(e) => form.setData('job_title', e.target.value)} className={inputClass} />
                                        </Field>
                                    </div>
                                </fieldset>

                                <fieldset className="mt-10 border-t border-stone-200 pt-10">
                                    <legend className="sr-only">{t('contact.project_title')}</legend>
                                    <p aria-hidden="true" className="font-display text-2xl font-semibold text-navy-900">
                                        {t('contact.project_title')}
                                    </p>
                                    <div className="mt-6 grid gap-5 sm:grid-cols-2">
                                        <Field id="service_id" label={t('contact.service')} error={errors.service_id} optionalLabel={optional}>
                                            <select {...a11y('service_id')} value={form.data.service_id} onChange={(e) => form.setData('service_id', e.target.value)} className={inputClass}>
                                                <option value="">{t('contact.not_sure')}</option>
                                                {services.map((s) => (
                                                    <option key={s.id} value={s.id}>
                                                        {s.title}
                                                    </option>
                                                ))}
                                            </select>
                                        </Field>
                                        <Field id="sector_id" label={t('contact.sector')} error={errors.sector_id} optionalLabel={optional}>
                                            <select {...a11y('sector_id')} value={form.data.sector_id} onChange={(e) => form.setData('sector_id', e.target.value)} className={inputClass}>
                                                <option value="">{t('contact.select')}</option>
                                                {sectors.map((s) => (
                                                    <option key={s.id} value={s.id}>
                                                        {s.name}
                                                    </option>
                                                ))}
                                            </select>
                                        </Field>
                                        <Field id="location" label={t('contact.location')} error={errors.location} optionalLabel={optional}>
                                            <input {...a11y('location')} autoComplete="address-level2" value={form.data.location} onChange={(e) => form.setData('location', e.target.value)} className={inputClass} />
                                        </Field>
                                        <Field id="area" label={t('contact.area')} error={errors.area} optionalLabel={optional}>
                                            <input {...a11y('area')} inputMode="numeric" value={form.data.area} onChange={(e) => form.setData('area', e.target.value)} className={inputClass} />
                                        </Field>
                                        <Field id="timeline" label={t('contact.timeline')} error={errors.timeline} optionalLabel={optional} className="sm:col-span-2">
                                            <div className="flex flex-wrap gap-2" role="radiogroup" aria-label={t("contact.timeline")}>
                                                {timelines.map((value) => (
                                                    <label key={value} className={cn('inline-flex min-h-11 cursor-pointer items-center rounded-full px-4 text-sm font-semibold ring-1 transition has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-navy-600', form.data.timeline === value ? 'bg-navy-700 text-white ring-navy-700' : 'bg-white text-navy-800 ring-stone-300 hover:ring-navy-300')}>
                                                        <input type="radio" name="timeline" value={value} checked={form.data.timeline === value} onChange={() => form.setData('timeline', value)} className="sr-only" />
                                                        {t(`contact.timelines.${value}`)}
                                                    </label>
                                                ))}
                                            </div>
                                        </Field>
                                        <Field id="message" label={t('contact.message')} error={errors.message} hint={t('contact.message_hint')} required optionalLabel={optional} className="sm:col-span-2">
                                            <textarea {...a11y('message', true)} required rows={5} value={form.data.message} onChange={(e) => form.setData('message', e.target.value)} className={cn(inputClass, 'min-h-36 resize-y')} />
                                        </Field>
                                    </div>
                                </fieldset>

                                <div aria-hidden="true" className="absolute -start-[9999px] h-0 overflow-hidden">
                                    <label>
                                        Website
                                        <input type="text" name="website" tabIndex={-1} autoComplete="off" value={form.data.website} onChange={(e) => form.setData('website', e.target.value)} />
                                    </label>
                                </div>

                                <div className="mt-10 flex flex-col items-start gap-4 border-t border-stone-200 pt-8 sm:flex-row sm:items-center sm:justify-between">
                                    <p className="text-sm text-stone-500">{t('contact.privacy')}</p>
                                    <button type="submit" disabled={form.processing} className={cn(buttonClass('primary'), 'w-full sm:w-auto')}>
                                        {form.processing ? (
                                            <span className="size-4 animate-spin rounded-full border-2 border-white/40 border-t-white" aria-hidden="true" />
                                        ) : (
                                            <Send className="size-4 rtl:-scale-x-100" aria-hidden="true" />
                                        )}
                                        {form.processing ? t('contact.sending') : t('contact.submit')}
                                    </button>
                                </div>
                            </form>
                        )}
                    </div>

                    <aside className="space-y-6 lg:col-span-4 lg:pt-28">
                        <div className="rounded-[2rem] bg-navy-800 p-8 text-white">
                            <h2 className="text-2xl font-semibold">{t('contact.direct_title')}</h2>
                            <p className="mt-2 text-navy-100/80">{t('contact.direct_text')}</p>
                            <div className="mt-6 flex flex-col gap-3">
                                {site.phone && (
                                    <a href={`tel:${site.phone.replace(/\s/g, '')}`} className={buttonClass('light')}>
                                        <Phone className="size-4" aria-hidden="true" />
                                        <span dir="ltr">{site.phone}</span>
                                    </a>
                                )}
                                {wa && (
                                    <a href={wa} target="_blank" rel="noopener noreferrer" className={buttonClass('outline-light')}>
                                        <WhatsAppIcon className="size-5" />
                                        {t('common.whatsapp')}
                                    </a>
                                )}
                            </div>
                        </div>
                        <dl className="space-y-5 rounded-[2rem] bg-white p-8 ring-1 ring-stone-200">
                            {[
                                { icon: Mail, label: t('contact.email_label'), value: site.email, href: site.email ? `mailto:${site.email}` : null },
                                { icon: MapPin, label: t('contact.office'), value: site.address, href: null },
                                { icon: Clock, label: t('contact.hours'), value: site.workingHours, href: null },
                            ]
                                .filter((row) => row.value)
                                .map(({ icon: RowIcon, label, value, href: link }) => (
                                    <div key={label} className="flex gap-4">
                                        <span className="inline-flex size-11 shrink-0 items-center justify-center rounded-xl bg-navy-50 text-navy-700">
                                            <RowIcon className="size-5" aria-hidden="true" />
                                        </span>
                                        <div className="min-w-0">
                                            <dt className="text-sm text-stone-500">{label}</dt>
                                            <dd className="font-semibold break-words text-navy-900">{link ? <a href={link} className="hover:underline">{value}</a> : value}</dd>
                                        </div>
                                    </div>
                                ))}
                        </dl>
                        {site.mapQuery && (
                            <div className="overflow-hidden rounded-[2rem] ring-1 ring-stone-200">
                                <iframe
                                    title={t('contact.map_title')}
                                    src={`https://www.google.com/maps?q=${encodeURIComponent(site.mapQuery)}&output=embed`}
                                    className="aspect-[4/3] w-full grayscale-[30%]"
                                    loading="lazy"
                                    referrerPolicy="no-referrer-when-downgrade"
                                />
                            </div>
                        )}
                    </aside>
                </div>
            </section>
        </>
    );
}
