<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;
    protected $fillable = ['bill_id', 'car_id', 'start_date', 'end_date'];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }
}
