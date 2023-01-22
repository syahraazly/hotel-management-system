<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;
    protected $table = 'type';
    protected $primaryKey = 'type_id';
    public $fillable = [
       'price', 'desc', 'photo_name','photo_path','type_name'
    ];

    public function Type(){
        return $this->hasMany(Room::class, 'room_id');
    }
}
