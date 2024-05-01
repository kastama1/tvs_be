<?php

namespace App\Models;

use App\Enum\FileTypeEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ID
 *
 * @property int $id
 *
 * ATTRIBUTES
 * @property string $name
 * @property string $path
 * @property FileTypeEnum $type
 *
 * TIMESTAMPS
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * FOREIGN KEYS
 *
 * RELATIONS
 */
class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'type',
    ];

    protected $casts = [
        'type' => FileTypeEnum::class
    ];
}
