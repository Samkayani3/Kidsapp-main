<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kid extends Model
{
    use HasFactory;
    protected $fillable = ['id','user_id', 'name', 'age', 'school', 'address', 'id_num', 'seat_number'];

    // Define the relationship with the User model (assuming one-to-many)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public $timestamps = false;
}
