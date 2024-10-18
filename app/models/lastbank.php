<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class lastbank extends Model
{
    protected $table = 'lastbank';
    protected $primaryKey = 'id';
    public $autoIncrement = true;

    public $timestamps = false;
    protected $fillable = [
        'idinicio',
        'idfin',
        'fuente',
        'ejercicio',
    ];
}
