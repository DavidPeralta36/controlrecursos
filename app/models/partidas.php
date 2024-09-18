<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class partidas extends Model
{
    protected $table = 'partidas';
    protected $primaryKey = 'id';
    public $autoIncrement = true;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'partida',
        'aportacion_federal',
        'aportacion_estatal',
        'descripcion',
        'req_auth_a_imb',
        'req_auth_af',
        'remuneracionPersonal',
        'adminInsumosMed',
        'gastosOperacion',
        'idcapitulo',
    ];
}
