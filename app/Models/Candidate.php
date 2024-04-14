<?php

namespace App\Models;

use App\Enum\FileTypeEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * ID
 * @property int $id
 *
 * ATTRIBUTES
 * @property string $name
 * @property string $campaign
 *
 * TIMESTAMPS
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * FOREIGN KEYS
 *
 * RELATIONS
 * @property ElectionParty $electionParty
 * @property Collection<File> $images
 *
 */
class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'campaign',
        'election_party_id',
    ];

    public function electionParty(): BelongsTo
    {
        return $this->belongsTo(ElectionParty::class);
    }

    public function images(): MorphToMany
    {
        return $this->morphToMany(File::class, 'fileable')->where('type', FileTypeEnum::IMAGE);
    }

    public function files(): MorphToMany
    {
        return $this->morphToMany(File::class, 'fileable');
    }
}
