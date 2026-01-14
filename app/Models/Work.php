<?php

namespace App\Models;

use Eloquent;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

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
 * @property-read \App\Models\Clinic|null $clinic
 * @property-read \App\Models\Mechanic|null $mechanic
 * @property-read \App\Models\WorkType|null $workType
 * @method static Builder<static>|Work whereCid($value)
 * @method static Builder<static>|Work whereCost($value)
 * @method static Builder<static>|Work whereMid($value)
 * @method static Builder<static>|Work wherePatient($value)
 * @method static Builder<static>|Work whereWtid($value)
 * @mixin Eloquent
 */
class Work extends Model
{
    /** @use HasFactory<WorkFactory> */
    use HasFactory;

    const STATE_NOT_START = 0;
    const STATE_IN_PROCCESS = 1;
    const STATE_COMPLETED = 2;
    const STATE_SENT = 3;

    const STATES = [
        self::STATE_NOT_START,
        self::STATE_IN_PROCCESS,
        self::STATE_COMPLETED,
        self::STATE_SENT
    ];

    protected $fillable = [
        'start',
        'end',
        'state',
        'count',
        'mid',
        'cid',
        'wtid',
        'comment',
        'patient',
        'cost',
    ];

    // Связь с WorkType
    public function workType() : BelongsTo
    {
        return $this->belongsTo(WorkType::class, 'wtid');
    }

    // Связь с Clinic
    public function clinic() : BelongsTo
    {
        return $this->belongsTo(Clinic::class, 'cid');
    }

    // Связь с Mechanic
    public function mechanic() : BelongsTo
    {
        return $this->belongsTo(Mechanic::class, 'mid');
    }
}
