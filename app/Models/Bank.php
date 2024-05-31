<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'code','user_id', 'expiry_date', 'cvv'];

    public function bill()
    {
        return $this->hasOne(Bill::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
