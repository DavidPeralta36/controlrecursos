<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class capitulos extends Model
{
    protected $table = 'capitulo';
    protected $primaryKey = 'id';
    public $autoIncrement = true;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'nombre',
        'descripcion',
    ];
}
