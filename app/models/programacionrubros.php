<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class programacionrubros extends Model
{
    protected $table = 'programacion_rubros';
    protected $primaryKey = 'id';
    public $autoIncrement = true;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'idrubro',
        'ejericio',
        'monto_programado',
        'idfuente',
    ];
}
