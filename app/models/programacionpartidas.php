<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class programacionpartidas extends Model
{
    protected $table = 'programacion_partidas';
    protected $primaryKey = 'id';
    public $autoIncrement = true;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'idpartida',
        'idcapitulo',
        'idrubro',
        'ejercicio',
        'idfuente',
        'monto_programado',
    ];
}
