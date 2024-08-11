<?php

namespace App\Models;

use App\Enum\ElectionTypeEnum;
use App\Enum\ElectionVotableTypeEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
 * @property bool $ended
 * @property ElectionVotableTypeEnum $votable
 * @property string $votableType
 * @property int $preferVotes
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
 * @property Collection<Candidate> $candidates
 * @property Collection<Vote> $votes
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
            get: fn (): bool => $this->publish_from <= $now && $now < $this->end_to,
        );
    }

    protected function active(): Attribute
    {
        $now = now();

        return new Attribute(
            get: fn (): bool => $this->start_from <= $now && $now < $this->end_to,
        );
    }

    protected function ended(): Attribute
    {
        $now = now();

        return new Attribute(
            get: fn (): bool => $this->end_to <= $now,
        );
    }

    protected function votable(): Attribute
    {
        return new Attribute(
            get: function (): ElectionVotableTypeEnum {
                switch ($this->type) {
                    case ElectionTypeEnum::PRESIDENTIAL_ELECTION:
                    case ElectionTypeEnum::SENATE_ELECTION:
                        return ElectionVotableTypeEnum::CANDIDATES;
                    default:
                        return ElectionVotableTypeEnum::ELECTION_PARTIES;
                }
            }
        );
    }

    protected function votableType(): Attribute
    {
        return new Attribute(
            get: function (): string {
                switch ($this->type) {
                    case ElectionTypeEnum::PRESIDENTIAL_ELECTION:
                    case ElectionTypeEnum::SENATE_ELECTION:
                        return Candidate::class;
                    default:
                        return ElectionParty::class;
                }
            }
        );
    }

    protected function preferVotes(): Attribute
    {
        return new Attribute(
            get: function (): int {
                switch ($this->type) {
                    case ElectionTypeEnum::CHAMBER_OF_DEPUTIES_ELECTION:
                    case ElectionTypeEnum::REGIONAL_ASSEMBLY_ELECTION:
                        return 4;
                    case ElectionTypeEnum::EUROPEAN_PARLIAMENT_ELECTION:
                        return 2;
                    case ElectionTypeEnum::MUNICIPAL_ASSEMBLY_ELECTION:
                        return 5;
                    default:
                        return 0;
                }
            }
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

    public function candidates(): BelongsToMany
    {
        return $this->belongsToMany(Candidate::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
}
