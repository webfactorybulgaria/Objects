<?php

namespace TypiCMS\Modules\Objects\Models;

use TypiCMS\Modules\Core\Shells\Models\BaseTranslation;

class ObjectTranslation extends BaseTranslation
{
    /**
     * get the parent model.
     */
    public function owner()
    {
        return $this->belongsTo('TypiCMS\Modules\Objects\Shells\Models\Object', 'object_id')->withoutGlobalScopes();
    }
}
