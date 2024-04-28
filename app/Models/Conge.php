<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Conge extends Model
{
    protected $fillable = ['user_id', 'start', 'end', 'reason', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
