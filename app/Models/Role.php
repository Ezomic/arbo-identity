<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['app_slug', 'name'])]
class Role extends Model
{
    /**
     * @return BelongsTo<AppDefinition, $this>
     */
    public function appDefinition(): BelongsTo
    {
        return $this->belongsTo(AppDefinition::class, 'app_slug', 'slug');
    }
}
