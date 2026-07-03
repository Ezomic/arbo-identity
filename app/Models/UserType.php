<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['id', 'name', 'app_slug'])]
class UserType extends Model
{
    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * @return BelongsTo<AppDefinition, $this>
     */
    public function appDefinition(): BelongsTo
    {
        return $this->belongsTo(AppDefinition::class, 'app_slug', 'slug');
    }
}
