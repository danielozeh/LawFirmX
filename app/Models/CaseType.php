<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CaseType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromTimestamp(strtotime($date))->format('d F Y h:i:s A');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::createFromTimestamp(strtotime($date))->format('d F Y h:i:s A');
    }

    public function case_details() {
        return $this->hasMany(CaseDetail::class);
    }
}
