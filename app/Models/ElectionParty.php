<?php

namespace App\Models;

use App\Enum\FileTypeEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * ID
 *
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
 * @property Collection<Election> $elections
 * @property Collection<Candidate> $candidates
 * @property Collection<File> $images
 */
class ElectionParty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'campaign',
    ];

    public function elections(): BelongsToMany
    {
        return $this->belongsToMany(Election::class);
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    public function images(): MorphToMany
    {
        return $this->morphToMany(File::class, 'fileable')->where('type', FileTypeEnum::IMAGE);
    }

    public function files(): MorphToMany
    {
        return $this->morphToMany(File::class, 'fileable')->where('type', FileTypeEnum::FILE);
    }
}
