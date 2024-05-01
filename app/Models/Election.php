<?php

namespace App\Models;

use App\Enum\ElectionTypeEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * ID
 *
 * @property int $id
 *
 * ATTRIBUTES
 * @property string $name
 * @property ElectionTypeEnum $type
 * @property string $info
 * @property bool $published
 * @property bool $active
 *
 * TIMESTAMPS
 * @property Carbon $publish_from
 * @property Carbon $start_from
 * @property Carbon $end_to
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * FOREIGN KEYS
 *
 * RELATIONS
 * @property Collection<ElectionParty> $electionParties
 */
class Election extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'info',
        'publish_from',
        'start_from',
        'end_to',
    ];

    protected $casts = [
        'publish_from' => 'datetime',
        'start_from' => 'datetime',
        'end_to' => 'datetime',
        'type' => ElectionTypeEnum::class,
    ];

    protected function published(): Attribute
    {
        $now = now();

        return new Attribute(
            get: fn (): bool => $this->publish_from <= $now && $this->end_to > $now,
        );
    }

    protected function active(): Attribute
    {
        $now = now();

        return new Attribute(
            get: fn (): bool => $this->start_from <= $now && $this->end_to > $now,
        );
    }

    public function scopePublished(Builder $query): Builder
    {
        $now = now();
        return $query->where('publish_from', '<=', $now)->where('end_to', '>', $now);
    }

    public function electionParties(): BelongsToMany
    {
        return $this->belongsToMany(ElectionParty::class);
    }
}
