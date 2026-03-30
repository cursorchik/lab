<?php

namespace App\Models;

use App\Interfaces\IWork;

use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Database\Factories\WorkFactory;

/**
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $start
 * @property string|null $end
 * @property int $state
 * @property string|null $comment
 * @property int $count
 * @method static WorkFactory factory($count = null, $state = [])
 * @method static Builder<static>|Work newModelQuery()
 * @method static Builder<static>|Work newQuery()
 * @method static Builder<static>|Work query()
 * @method static Builder<static>|Work whereComment($value)
 * @method static Builder<static>|Work whereCount($value)
 * @method static Builder<static>|Work whereCreatedAt($value)
 * @method static Builder<static>|Work whereEnd($value)
 * @method static Builder<static>|Work whereId($value)
 * @method static Builder<static>|Work whereStart($value)
 * @method static Builder<static>|Work whereState($value)
 * @method static Builder<static>|Work whereUpdatedAt($value)
 * @property int $mid mechanicID
 * @property int $cid clinicID
 * @property int $wtid workTypeID
 * @property string $patient
 * @property int $cost
 * @property-read Clinic|null $clinic
 * @property-read Mechanic|null $mechanic
 * @property-read WorkType|null $workType
 * @method static Builder<static>|Work whereCid($value)
 * @method static Builder<static>|Work whereCost($value)
 * @method static Builder<static>|Work whereMid($value)
 * @method static Builder<static>|Work wherePatient($value)
 * @method static Builder<static>|Work whereWtid($value)
 * @property-read Collection<int, WorkType> $workTypes
 * @property-read int|null $work_types_count
 * @mixin Eloquent
 */
class Work extends Model implements IWork
{
    /** @use HasFactory<WorkFactory> */
    use HasFactory;

    protected $fillable = [
        'start',
        'end',
        'state',
        'mid',
        'cid',
        'comment',
        'patient',
        'cost',
    ];

	// Связь "многие ко многим" с WorkType через таблицу work_work_type
	public function workTypes() : BelongsToMany
	{
		return $this->belongsToMany(WorkType::class, 'work_work_type')
			->withPivot('count')
			->withTimestamps();
	}

    public function clinic() : BelongsTo
    {
        return $this->belongsTo(Clinic::class, 'cid');
    }

    public function mechanic() : BelongsTo
    {
        return $this->belongsTo(Mechanic::class, 'mid');
    }
}
