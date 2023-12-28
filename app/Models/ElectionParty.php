<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
 *
 */
class ElectionParty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'campaign',
    ];
}
