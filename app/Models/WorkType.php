<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Database\Factories\WorkTypeFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $name
 * @property float                      $cost
 * @method static WorkTypeFactory factory($count = null, $state = [])
 * @method static Builder<static>|WorkType newModelQuery()
 * @method static Builder<static>|WorkType newQuery()
 * @method static Builder<static>|WorkType query()
 * @method static Builder<static>|WorkType whereCost($value)
 * @method static Builder<static>|WorkType whereCreatedAt($value)
 * @method static Builder<static>|WorkType whereId($value)
 * @method static Builder<static>|WorkType whereName($value)
 * @method static Builder<static>|WorkType whereUpdatedAt($value)
 * @property-read Collection<int, Work> $works
 * @property-read int|null              $works_count
 * @mixin Eloquent
 */
class WorkType extends Model
{
    /** @use HasFactory<WorkTypeFactory> */
    use HasFactory;

	protected $fillable = ['name', 'cost_clinic', 'cost_mechanic'];

	public function works() : BelongsToMany
	{
		return $this->belongsToMany(Work::class, 'work_work_type')
			->withPivot('count')
			->withTimestamps();
	}
}
