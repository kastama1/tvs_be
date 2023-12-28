<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
 * @property Collection<Election> $elections
 *
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
}
