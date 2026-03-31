<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MechanicWorkLock extends Model
{
	protected $fillable = ['work_id', 'mechanic_id', 'locked_at'];
	protected $casts = ['locked_at' => 'datetime'];

	public function work() : BelongsTo { return $this->belongsTo(Work::class); }
	public function mechanic() : BelongsTo { return $this->belongsTo(Mechanic::class); }
}
