<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class clues extends Model
{
    protected $table = 'clues';
    protected $primaryKey = 'id';
    public $autoIncrement = true;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'clue',
        'clue_homologada',
        'nombre_clue',
    ];
}
