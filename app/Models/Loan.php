<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $user_receiver_id
 * @property int $user_giver_id
 * @property int $user_receipt_id
 * @property Carbon $receipt_date
 */
class Loan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_giver_id',
        'user_receiver_id',
        'user_receipt_id',
        'receipt_date',
    ];

    // Relacionamento com o usuário que empresta
    public function userGiver()
    {
        return $this->belongsTo(User::class, 'user_giver_id');
    }

    // Relacionamento com o usuário que empresta
    public function userReceipt()
    {
        return $this->belongsTo(User::class, 'user_receipt_id');
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
