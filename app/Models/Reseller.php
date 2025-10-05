<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reseller extends Model
{
    use HasFactory;

    protected $table = 'users';
    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'reseller_id', 'marketing');
    // }
}
