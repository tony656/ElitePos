<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class debtsModel extends Model
{
    use HasFactory;

    protected $table = 'debts';

    protected $fillable = ['cName','cId','debtId','orderId','amount'];
}
