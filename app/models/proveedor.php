<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class proveedor extends Model
{
    protected $table = 'proveedores';
    protected $primaryKey = 'id';
    public $autoIncrement = true;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'rfc',
        'proveedor',
        'numero_cuenta',
    ];
}
