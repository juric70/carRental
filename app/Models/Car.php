<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    protected $fillable = ['license_plate', 'brand', 'model', 'year', 'color', 'user_id', 'customer_service_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customerService()
    {
        return $this->belongsTo(CustomerService::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}
