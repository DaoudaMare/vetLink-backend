<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payments extends Model
{
    use HasFactory;

    



    public function commande()
{
    return $this->belongsTo(Commande::class);
}

public function status()
{
    return $this->belongsTo(Status::class, 'payment_status_id');
}

public function method()
{
    return $this->belongsTo(payment_methodes::class, 'payment_method_id');
}

}
