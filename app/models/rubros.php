<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class rubros extends Model
{
    protected $table = 'rubros';
    protected $primaryKey = 'id';
    public $autoIncrement = true;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'descripcion',
    ];
}
