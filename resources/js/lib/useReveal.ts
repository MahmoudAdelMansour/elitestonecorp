import { useEffect } from 'react';

/** Adds `is-visible` to every [data-reveal] element as it scrolls into view. */
export function useReveal(dependency?: unknown) {
    useEffect(() => {
        const elements = Array.from(document.querySelectorAll<HTMLElement>('[data-reveal]:not(.is-visible)'));
        if (!('IntersectionObserver' in window)) {
            elements.forEach((el) => el.classList.add('is-visible'));
            return;
        }
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            },
            { rootMargin: '0px 0px -8% 0px', threshold: 0.08 },
        );
        elements.forEach((el) => observer.observe(el));
        return () => observer.disconnect();
    }, [dependency]);
}
