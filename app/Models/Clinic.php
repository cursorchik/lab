<?php

namespace App\Models;

use Eloquent;
use Illuminate\Support\Carbon;
use Database\Factories\ClinicFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $name
 * @method static ClinicFactory factory($count = null, $state = [])
 * @method static Builder<static>|Clinic newModelQuery()
 * @method static Builder<static>|Clinic newQuery()
 * @method static Builder<static>|Clinic query()
 * @method static Builder<static>|Clinic whereCreatedAt($value)
 * @method static Builder<static>|Clinic whereId($value)
 * @method static Builder<static>|Clinic whereName($value)
 * @method static Builder<static>|Clinic whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Clinic extends Model
{
    /** @use HasFactory<ClinicFactory> */
    use HasFactory;

    protected $fillable = ['name'];

	public function works()
	{
		return $this->hasMany(Work::class, 'cid');
	}
}
