<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * ID
 *
 * @property int $id
 *
 * ATTRIBUTES
 * @property int $votable_id
 * @property string $votable_type
 *
 * TIMESTAMPS
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * FOREIGN KEYS
 *
 * RELATIONS
 * @property Collection<ElectionParty|Candidate> $votable
 * @property User $user
 * @property Election $election
 */
class Vote extends Model
{
    protected $fillable = [
        'votable_type',
        'votable_id',
        'election_id',
    ];

    public function scopeOfElection(Builder $query, Election $election): Builder
    {
        return $query->where('election_id', '=', $election->id);
    }

    public function votable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }
}
