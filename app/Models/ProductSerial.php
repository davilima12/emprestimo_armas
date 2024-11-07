<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSerial extends Model
{
    use HasFactory;

    protected $table = 'product_serials';

    protected $fillable = [
        'product_id',
        'serial_number',
        'is_loaned',
    ];

    // Relacionamento com a tabela Products
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
