<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_giver_id',
        'user_receiver_id',
    ];

    // Relacionamento com o usuário que empresta
    public function userGiver()
    {
        return $this->belongsTo(User::class, 'user_giver_id');
    }

    // Relacionamento com o usuário que recebe
    public function userReceiver()
    {
        return $this->belongsTo(User::class, 'user_receiver_id');
    }

    // Relacionamento com os produtos emprestados
    public function loanedProducts()
    {
        return $this->hasMany(LoanProduct::class);
    }
}
