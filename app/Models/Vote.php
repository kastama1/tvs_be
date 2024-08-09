<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * ID
 *
 * @property int $id
 *
 * ATTRIBUTES
 * @property int $votable_id
 * @property string $votable_type
 * @property string $hash
 * @property string $previous_hash
 * @property string $election_id
 * @property string $vote_id
 * @property string $user_id
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
 * @property Collection<Vote> $votes
 */
class Vote extends Model
{
    protected $fillable = [
        'votable_type',
        'hash',
        'previous_hash',
        'votable_id',
        'election_id',
        'vote_id',
    ];

    protected $hidden = [
        'hash',
        'previous_hash',
        'user_id',
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

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
}
