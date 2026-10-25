import {
    Building,
    ClipboardCheck,
    ConciergeBell,
    Droplets,
    Factory,
    Gem,
    GraduationCap,
    Hammer,
    HardHat,
    HeartHandshake,
    Hospital,
    Layers,
    Lightbulb,
    type LucideIcon,
    PaintRoller,
    PenTool,
    ShieldCheck,
    Sparkles,
    Store,
    Syringe,
    Wheat,
} from 'lucide-react';

/** Icon names an admin can choose from (kept in sync with App\Filament\Support\IconOptions). */
export const icons: Record<string, LucideIcon> = {
    factory: Factory,
    gem: Gem,
    'shield-check': ShieldCheck,
    hammer: Hammer,
    droplets: Droplets,
    syringe: Syringe,
    'pen-tool': PenTool,
    'paint-roller': PaintRoller,
    'clipboard-check': ClipboardCheck,
    wheat: Wheat,
    hospital: Hospital,
    'concierge-bell': ConciergeBell,
    'graduation-cap': GraduationCap,
    store: Store,
    building: Building,
    layers: Layers,
    'hard-hat': HardHat,
    sparkles: Sparkles,
    lightbulb: Lightbulb,
    'heart-handshake': HeartHandshake,
};

export default function Icon({ name, className }: { name: string; className?: string }) {
    const Component = icons[name] ?? Layers;
    return <Component className={className} aria-hidden="true" strokeWidth={1.6} />;
}

export function WhatsAppIcon({ className }: { className?: string }) {
    return (
        <svg viewBox="0 0 24 24" className={className} aria-hidden="true" fill="currentColor">
            <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21c5.46 0 9.91-4.45 9.91-9.91S17.5 2 12.04 2Zm0 18.15c-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.25-8.24 4.54 0 8.24 3.7 8.24 8.24 0 4.55-3.7 8.24-8.24 8.24Zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.13-.16.24-.64.8-.78.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.01-.38.11-.5.11-.11.25-.29.37-.43.13-.15.17-.25.25-.42.08-.16.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.22.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.14-1.18-.06-.1-.22-.16-.47-.28Z" />
        </svg>
    );
}

export function InstagramIcon({ className }: { className?: string }) {
    return (
        <svg viewBox="0 0 24 24" className={className} aria-hidden="true" fill="none" stroke="currentColor" strokeWidth={1.7}>
            <rect x="3" y="3" width="18" height="18" rx="5" />
            <circle cx="12" cy="12" r="4" />
            <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none" />
        </svg>
    );
}

export function FacebookIcon({ className }: { className?: string }) {
    return (
        <svg viewBox="0 0 24 24" className={className} aria-hidden="true" fill="currentColor">
            <path d="M13.5 21v-7.5h2.5l.4-3h-2.9V8.6c0-.87.25-1.46 1.5-1.46h1.55V4.47A20.6 20.6 0 0 0 14.3 4.3c-2.24 0-3.78 1.37-3.78 3.88v2.32H8v3h2.52V21h2.98Z" />
        </svg>
    );
}

export function LinkedinIcon({ className }: { className?: string }) {
    return (
        <svg viewBox="0 0 24 24" className={className} aria-hidden="true" fill="currentColor">
            <path d="M6.94 8.5H3.56V20h3.38V8.5ZM5.25 3a1.97 1.97 0 1 0 0 3.94 1.97 1.97 0 0 0 0-3.94ZM20.44 13.4c0-3.1-1.66-4.54-3.87-4.54a3.34 3.34 0 0 0-3.03 1.67V8.5h-3.37V20h3.37v-6.07c0-1.6.3-3.15 2.28-3.15 1.96 0 1.99 1.83 1.99 3.25V20h3.37l-.01-6.6Z" />
        </svg>
    );
}
