<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function sponser(){
        return $this->belongsTo(Sponser::class);
    }
}
