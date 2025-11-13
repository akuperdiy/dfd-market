<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBatch extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'batch_no', 'qty', 'expiry'];

    protected $casts = [
        'expiry' => 'date',
        'qty' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

