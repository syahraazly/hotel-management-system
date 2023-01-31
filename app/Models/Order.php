<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    public $fillable = [
        'order_number', 
        'customer',
        'customer_email',
        'order_date',
        'check_in',
        'check_out',
        'guest_name',
        'type_id',
        'user_id',
    ];
}
