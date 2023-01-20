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
        'type_name', 'price', 'desc', 'photo'
    ];

    public function Room(){
        return $this->hasMany(Room::class, 'type_id');
    }
}
