<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class registrobancos extends Model
{
    protected $table = 'rgbco';
    protected $primaryKey = 'id';
    public $autoIncrement = true;

    protected $fillable = [
        'fechas',
        'mes',
        'forma_pago',
        'rfc',
        'proveedor',
        'factura',
        'parcial',
        'depositos',
        'retiros',
        'saldo',
        'r',
        'partida',
        'fecha_factura',
        'folio_fiscal',
        'tipo_adjudicacion',
        'num_adj_contrato',
        'num_suficiencia_presupuestal',
        'orden_servicio_compra',
        'clc',
        'poliza',
        'numero_cuenta_proovedor',
        'referencia_bancaria',
        'clue',
        'nombre_clue',
        'nombre_partida',
        'mes_servicio',
        'metodo_pago',
        'idfuente',
        'ejercicio'
    ];
}
