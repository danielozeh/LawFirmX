<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class CaseDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'case_number',
        'case_type_id',
        'stage_of_case',
        'act',
        'filing_number',
        'registration_number',
        'registration_date',
        'first_hearing_date',
        'priority'
    ];

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromTimestamp(strtotime($date))->format('d F Y h:i:s A');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::createFromTimestamp(strtotime($date))->format('d F Y h:i:s A');
    }

    public function client() {
        return $this->belongsTo(Client::class);
    }
}
