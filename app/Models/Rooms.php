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
        'room_number', 'type_id'
    ];

    public function Rooms(){
        return $this->hasOne(Type::class, 'type_id');
    }
}
