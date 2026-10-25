import { cn } from '@/lib/cn';

export default function Logo({ light = false, className }: { light?: boolean; className?: string }) {
    return (
        <span className={cn('flex items-center gap-3', className)} dir="ltr">
            <span className={cn('mark-mask size-9 shrink-0 sm:size-10 transition-colors', light ? 'text-white' : 'text-navy-700')} aria-hidden="true" />
            <span className="flex flex-col leading-none whitespace-nowrap">
                <span className={cn('font-display text-[1.45rem] font-semibold tracking-tight transition-colors', light ? 'text-white' : 'text-navy-800')} style={{ fontFamily: 'Vollkorn, serif' }}>
                    Elite Stone
                </span>
                <span className={cn('mt-1 hidden text-[0.6rem] font-semibold sm:block tracking-[0.2em] uppercase transition-colors', light ? 'text-stone-300' : 'text-stone-500')}>From Concept to Creation</span>
            </span>
        </span>
    );
}
