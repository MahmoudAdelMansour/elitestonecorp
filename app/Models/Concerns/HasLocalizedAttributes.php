<?php

namespace App\Models\Concerns;

trait HasLocalizedAttributes
{
    /**
     * Read a bilingual attribute (e.g. "title" => title_ar / title_en) for the
     * current locale, falling back to English when the translation is empty.
     */
    public function localized(string $attribute, ?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();

        return $this->getAttribute("{$attribute}_{$locale}")
            ?: $this->getAttribute("{$attribute}_en");
    }

    /**
     * Split a "one item per line" bilingual attribute into a clean list.
     *
     * @return list<string>
     */
    public function localizedLines(string $attribute): array
    {
        return collect(preg_split('/\R/u', (string) $this->localized($attribute)))
            ->map(fn (string $line) => preg_replace('/^[\s\-•]+|[\s\-•]+$/u', '', $line))
            ->filter()
            ->values()
            ->all();
    }
}
