<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property float $cost
 * @method static \Database\Factories\WorkTypeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkType whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class WorkType extends Model
{
    /** @use HasFactory<\Database\Factories\WorkTypeFactory> */
    use HasFactory;

    protected $fillable = ['name', 'cost'];

}
