<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayOutRequest extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
    public function account(){
        return $this->belongsTo(Account::class);
    }
}
