<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    use HasFactory;

    protected $fillable = ['return_id', 'product_id', 'qty', 'price'];

    protected $casts = [
        'qty' => 'integer',
        'price' => 'decimal:2',
    ];

    public function returnModel()
    {
        return $this->belongsTo(ReturnModel::class, 'return_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

