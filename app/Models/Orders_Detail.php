<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders_Detail extends Model
{
    use HasFactory;

    protected $table = 'orders_details';
    protected $primaryKey = 'order_details_id';

    public $fillable = [
        'order_id',
        'room_id',
        'access_date',
        'price',
        'create_at',
        'updated_at'
    ];
}
