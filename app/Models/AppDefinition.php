<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['slug', 'name', 'base_url'])]
class AppDefinition extends Model
{
    protected $primaryKey = 'slug';

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * @return HasMany<Role, $this>
     */
    public function roles(): HasMany
    {
        return $this->hasMany(Role::class, 'app_slug', 'slug');
    }

    /**
     * Whether $url belongs to this app's own domain — an exact scheme+host
     * match, not a string prefix. `Str::startsWith($url, $this->base_url)`
     * would wrongly accept `https://case-officers.test.attacker.com` for a
     * base_url of `https://case-officers.test`, so both URLs are parsed and
     * compared structurally instead.
     */
    public function ownsUrl(string $url): bool
    {
        $target = parse_url($url);
        $base = parse_url($this->base_url);

        if (! isset($target['scheme'], $target['host'], $base['scheme'], $base['host'])) {
            return false;
        }

        return $target['scheme'] === $base['scheme']
            && $target['host'] === $base['host'];
    }
}
