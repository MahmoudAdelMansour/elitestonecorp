import { Link, usePage } from '@inertiajs/react';
import { Download, Mail, MapPin, Menu, Phone, X } from 'lucide-react';
import { type ReactNode, useEffect, useState } from 'react';
import { FacebookIcon, InstagramIcon, LinkedinIcon, WhatsAppIcon } from '@/components/Icon';
import Logo from '@/components/Logo';
import { buttonClass, Seo } from '@/components/ui';
import { cn } from '@/lib/cn';
import { useI18n } from '@/lib/i18n';
import { useReveal } from '@/lib/useReveal';
import type { SharedProps } from '@/types';

export function whatsappUrl(number: string | null, message: string) {
    return number ? `https://wa.me/${number.replace(/\D/g, '')}?text=${encodeURIComponent(message)}` : null;
}

function useNav() {
    const { t, href } = useI18n();
    return [
        { label: t('nav.home'), href: href('/'), match: (u: string) => /^\/(en|ar)\/?$/.test(u) },
        { label: t('nav.about'), href: href('/about'), match: (u: string) => u.includes('/about') },
        { label: t('nav.services'), href: href('/services'), match: (u: string) => u.includes('/services') },
        { label: t('nav.projects'), href: href('/projects'), match: (u: string) => u.includes('/projects') },
        { label: t('nav.contact'), href: href('/contact'), match: (u: string) => u.includes('/contact') },
    ];
}

function Header() {
    const { url, props } = usePage<SharedProps>();
    const { t, href, locale } = useI18n();
    const nav = useNav();
    const [scrolled, setScrolled] = useState(false);
    const [open, setOpen] = useState(false);
    const path = url.split('?')[0];

    useEffect(() => {
        const onScroll = () => setScrolled(window.scrollY > 24);
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
        return () => window.removeEventListener('scroll', onScroll);
    }, []);

    useEffect(() => setOpen(false), [url]);

    useEffect(() => {
        document.body.style.overflow = open ? 'hidden' : '';
        const onKey = (e: KeyboardEvent) => e.key === 'Escape' && setOpen(false);
        window.addEventListener('keydown', onKey);
        return () => window.removeEventListener('keydown', onKey);
    }, [open]);

    const solid = scrolled || open;

    return (
        <header className={cn('fixed inset-x-0 top-0 z-50 transition-all duration-300', solid ? 'bg-white/95 shadow-[0_1px_0_rgba(18,26,46,0.08)] backdrop-blur-md' : 'bg-transparent')}>
            <div className="container-site flex h-20 items-center justify-between gap-6">
                <Link href={href('/')} aria-label="Elite Stone" className="rounded-md">
                    <Logo light={!solid} />
                </Link>

                <nav aria-label="Main" className="hidden lg:block">
                    <ul className="flex items-center gap-1">
                        {nav.map((item) => {
                            const active = item.match(path);
                            return (
                                <li key={item.href}>
                                    <Link
                                        href={item.href}
                                        prefetch
                                        aria-current={active ? 'page' : undefined}
                                        className={cn(
                                            'relative rounded-full px-4 py-2 text-[0.95rem] font-semibold transition',
                                            solid ? 'text-navy-900 hover:bg-navy-50' : 'text-white/90 hover:bg-white/10 hover:text-white',
                                            active && (solid ? 'bg-navy-50 text-navy-700' : 'bg-white/10 text-white'),
                                        )}
                                    >
                                        {item.label}
                                    </Link>
                                </li>
                            );
                        })}
                    </ul>
                </nav>

                <div className="flex items-center gap-1 sm:gap-2">
                    <a
                        href={props.alternateUrl}
                        hrefLang={locale === 'ar' ? 'en' : 'ar'}
                        aria-label={t('nav.switch_language_label')}
                        className={cn(
                            'inline-flex min-h-11 items-center rounded-full px-4 text-sm font-bold transition',
                            solid ? 'text-navy-800 hover:bg-navy-50' : 'text-white hover:bg-white/10',
                            locale === 'en' ? 'font-arabic' : 'font-sans',
                        )}
                        style={{ fontFamily: locale === 'en' ? 'IBM Plex Sans Arabic, sans-serif' : 'Manrope, sans-serif' }}
                    >
                        {t('nav.switch_language')}
                    </a>
                    <Link href={href('/contact')} className={cn(buttonClass(solid ? 'primary' : 'light'), 'hidden min-h-11 md:inline-flex')}>
                        {t('nav.quote')}
                    </Link>
                    <button
                        type="button"
                        onClick={() => setOpen((v) => !v)}
                        aria-expanded={open}
                        aria-controls="mobile-menu"
                        aria-label={open ? t('nav.close') : t('nav.menu')}
                        className={cn('inline-flex size-11 cursor-pointer items-center justify-center rounded-full transition lg:hidden', solid ? 'text-navy-900 hover:bg-navy-50' : 'text-white hover:bg-white/10')}
                    >
                        {open ? <X className="size-6" /> : <Menu className="size-6" />}
                    </button>
                </div>
            </div>

            <div id="mobile-menu" hidden={!open} className="h-[calc(100dvh-5rem)] overflow-y-auto border-t border-stone-200 bg-white lg:hidden">
                <nav aria-label="Mobile" className="container-site flex flex-col py-6">
                    {nav.map((item) => (
                        <Link
                            key={item.href}
                            href={item.href}
                            aria-current={item.match(path) ? 'page' : undefined}
                            className={cn('border-b border-stone-100 py-4 text-2xl font-semibold text-navy-900', item.match(path) && 'text-navy-600')}
                        >
                            {item.label}
                        </Link>
                    ))}
                    <Link href={href('/contact')} className={cn(buttonClass('primary'), 'mt-8 w-full')}>
                        {t('nav.quote')}
                    </Link>
                    {props.site.phone && (
                        <a href={`tel:${props.site.phone.replace(/\s/g, '')}`} className={cn(buttonClass('secondary'), 'mt-3 w-full')}>
                            <Phone className="size-4" aria-hidden="true" />
                            <span dir="ltr">{props.site.phone}</span>
                        </a>
                    )}
                </nav>
            </div>
        </header>
    );
}

function Footer() {
    const { site, navServices } = usePage<SharedProps>().props;
    const { t, href } = useI18n();
    const nav = useNav();
    const year = new Date().getFullYear();
    const socials = [
        { url: site.instagram, label: 'Instagram', Icon: InstagramIcon },
        { url: site.facebook, label: 'Facebook', Icon: FacebookIcon },
        { url: site.linkedin, label: 'LinkedIn', Icon: LinkedinIcon },
    ].filter((s) => s.url);

    return (
        <footer className="relative overflow-hidden bg-navy-950 text-navy-100">
            <div className="mark-mask absolute -start-24 -bottom-24 size-[26rem] text-white/[0.03]" aria-hidden="true" />
            <div className="container-site relative grid gap-12 py-20 md:grid-cols-2 lg:grid-cols-12">
                <div className="lg:col-span-4">
                    <Logo light />
                    <p className="mt-6 max-w-sm leading-relaxed text-navy-100/70">{t('footer.about')}</p>
                    {site.profileUrl && (
                        <a href={site.profileUrl} className={cn(buttonClass('outline-light'), 'mt-8 min-h-11 text-sm')}>
                            <Download className="size-4" aria-hidden="true" />
                            {t('common.download_profile')}
                        </a>
                    )}
                </div>
                <div className="lg:col-span-2">
                    <h2 className="font-sans text-sm font-bold tracking-widest text-white uppercase rtl:tracking-normal">{t('footer.company')}</h2>
                    <ul className="mt-5 space-y-3">
                        {nav.map((item) => (
                            <li key={item.href}>
                                <Link href={item.href} className="text-navy-100/70 transition hover:text-white">
                                    {item.label}
                                </Link>
                            </li>
                        ))}
                    </ul>
                </div>
                <div className="lg:col-span-3">
                    <h2 className="font-sans text-sm font-bold tracking-widest text-white uppercase rtl:tracking-normal">{t('footer.services')}</h2>
                    <ul className="mt-5 space-y-3">
                        {navServices.map((service) => (
                            <li key={service.slug}>
                                <Link href={href(`/services/${service.slug}`)} className="text-navy-100/70 transition hover:text-white">
                                    {service.title}
                                </Link>
                            </li>
                        ))}
                    </ul>
                </div>
                <div className="lg:col-span-3">
                    <h2 className="font-sans text-sm font-bold tracking-widest text-white uppercase rtl:tracking-normal">{t('footer.contact')}</h2>
                    <ul className="mt-5 space-y-4 text-navy-100/80">
                        {site.phone && (
                            <li>
                                <a href={`tel:${site.phone.replace(/\s/g, '')}`} className="flex items-center gap-3 transition hover:text-white">
                                    <Phone className="size-4 shrink-0" aria-hidden="true" />
                                    <span dir="ltr">{site.phone}</span>
                                </a>
                            </li>
                        )}
                        {site.email && (
                            <li>
                                <a href={`mailto:${site.email}`} className="flex items-center gap-3 break-all transition hover:text-white">
                                    <Mail className="size-4 shrink-0" aria-hidden="true" />
                                    {site.email}
                                </a>
                            </li>
                        )}
                        {site.address && (
                            <li className="flex items-center gap-3">
                                <MapPin className="size-4 shrink-0" aria-hidden="true" />
                                {site.address}
                            </li>
                        )}
                    </ul>
                    {socials.length > 0 && (
                        <div className="mt-8">
                            <p className="text-sm font-semibold text-white">{t('footer.follow')}</p>
                            <div className="mt-3 flex gap-2">
                                {socials.map(({ url, label, Icon }) => (
                                    <a key={label} href={url!} target="_blank" rel="noopener noreferrer" aria-label={label} className="inline-flex size-11 items-center justify-center rounded-full border border-white/15 transition hover:border-white/50 hover:bg-white/5">
                                        <Icon className="size-5" />
                                    </a>
                                ))}
                            </div>
                        </div>
                    )}
                </div>
            </div>
            <div className="border-t border-white/10">
                <div className="container-site flex flex-col gap-2 py-6 text-sm text-navy-100/60 sm:flex-row sm:items-center sm:justify-between">
                    <p>
                        © {year} Elite Stone. {t('footer.rights')}
                    </p>
                    <p className="font-display tracking-wide">{t('tagline')}</p>
                </div>
            </div>
        </footer>
    );
}

function WhatsAppButton() {
    const { site } = usePage<SharedProps>().props;
    const { t } = useI18n();
    const url = whatsappUrl(site.whatsapp, t('common.whatsapp_message'));
    if (!url) return null;
    return (
        <a
            href={url}
            target="_blank"
            rel="noopener noreferrer"
            aria-label={t('common.whatsapp')}
            className="group fixed end-5 bottom-5 z-40 inline-flex size-14 items-center justify-center rounded-full bg-[#1f9d55] text-white shadow-xl shadow-black/20 transition hover:scale-105 hover:bg-[#188a49] sm:end-7 sm:bottom-7"
        >
            <WhatsAppIcon className="size-7" />
            <span className="pointer-events-none absolute end-full me-3 hidden rounded-full bg-navy-950 px-3 py-1.5 text-sm font-semibold whitespace-nowrap opacity-0 transition group-hover:opacity-100 sm:block">{t('common.whatsapp')}</span>
        </a>
    );
}

export default function Layout({ children }: { children: ReactNode }) {
    const { url } = usePage();
    const { t } = useI18n();
    useReveal(url);

    return (
        <>
            <Seo />
            <a href="#main" className="sr-only z-[60] rounded-full bg-white px-4 py-2 font-semibold text-navy-900 focus:not-sr-only focus:fixed focus:start-4 focus:top-4">
                {t('skip')}
            </a>
            <Header />
            <main id="main">{children}</main>
            <Footer />
            <WhatsAppButton />
        </>
    );
}
