<?php

namespace App\Models\Concerns;

trait HasLocalizedAttributes
{
    public function tr(string $attribute): mixed
    {
        if (app()->getLocale() === config('app.default_locale')) {
            return $this->getAttribute($attribute);
        }

        $localized = $this->getAttribute($attribute.'_en');

        return filled($localized) ? $localized : $this->getAttribute($attribute);
    }
}
