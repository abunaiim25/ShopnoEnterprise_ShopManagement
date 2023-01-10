<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    use HasFactory;

    protected $table = 'purchase_returns';
    protected $fillable = [
        'product_name',
        'category_id',
        'brand',
        'product_quantity',
        'return_reason',
        'comment',
        'return_status',
    ];
}
