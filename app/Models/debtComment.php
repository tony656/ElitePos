<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class debtComment extends Model
{
    use HasFactory;

    protected $table = 'debt_comments';

    protected $fillable = [
        'order_id',
        'order_name',
        'user_id',
        'user_name',
        'comment',
        'account',
    ];
}
