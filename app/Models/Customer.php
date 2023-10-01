<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_title',
        'email',
        'first_name',
        'last_name',
        'registered_since',
        'phone',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
