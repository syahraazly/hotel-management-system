<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    use HasFactory;
    protected $table = 'rooms';
    protected $primaryKey = 'room_id';
    public $fillable = [
        'room_number', 'room_type', 'status'
    ];

    public function Type(){
        return $this->belongsTo(Type::class, 'type_id');
    }
}
