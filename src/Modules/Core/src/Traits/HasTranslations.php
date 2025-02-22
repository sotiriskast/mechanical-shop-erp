<?php
namespace Modules\Core\src\Traits;

use Spatie\Translatable\HasTranslations as BaseHasTranslations;

trait HasTranslations
{
    use BaseHasTranslations;

    public function getTranslatedAttribute($key, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->getTranslation($key, $locale);
    }
}
