<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'company_id',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'job_code', 'code');
    }
        public function Company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
