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
 * @property string $type
 * @property string $info
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
 *
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
    ];
}
