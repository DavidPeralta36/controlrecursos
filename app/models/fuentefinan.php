<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class fuentefinan extends Model
{
    protected $table = 'fuentefinan';
    protected $primaryKey = 'id';
    public $autoIncrement = true;

    protected $fillable = [
        'nombre_fuente',
    ];
}
