<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'bio',
        'job_code',
        'verification_code',
        'verified',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_code', 'code');
    }
}
