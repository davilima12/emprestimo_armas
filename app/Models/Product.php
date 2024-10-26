<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'product_type',
    ];

    public function serialNumbers()
    {
        return $this->hasMany(ProductSerial::class);
    }

    public function loanedProducts()
    {
        return $this->hasMany(LoanProduct::class, 'product_id');
    }
}
