<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'loan_id',
        'product_id',
        'product_serial_id',
        'magazines',
        'ammunition'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productSerial()
    {
        return $this->belongsTo(ProductSerial::class, 'product_serial_id', 'id');
    }

}
