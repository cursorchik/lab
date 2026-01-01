<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @method static \Database\Factories\MechanicFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mechanic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mechanic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mechanic query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mechanic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mechanic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mechanic whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mechanic whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Mechanic extends Model
{
    /** @use HasFactory<\Database\Factories\MechanicFactory> */
    use HasFactory;

    protected $fillable = ['name'];
}
