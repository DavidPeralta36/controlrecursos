export const U013=[
    { field: 'fechas', headerName: 'Fecha', filter: true, sortable: true },
    { field: 'mes', headerName: 'Mes', filter: true, sortable: true },
    { field: 'forma_pago', headerName: 'Forma de pago', filter: true, sortable: true },
    { field: 'metodo_pago', headerName: 'Metodo de pago', filter: true, sortable: true },
    { field: 'rfc', headerName: 'RFC', filter: true, sortable: true },
    { field: 'proveedor', headerName: 'Proveedor', filter: true, sortable: true  },
    { field: 'factura', headerName: 'Factura', filter: true, sortable: true  },
    { 
        field: 'parcial', 
        headerName: 'Parcial', 
        valueFormatter: formatCurrency , 
        cellClass: (params) => params.value ? 'partial-cell' : '',
    },
    { 
        field: 'depositos', 
        headerName: 'Depositos', 
        valueFormatter: formatCurrency , 
        cellClass: (params) => params.value ? 'deposit-cell' : '',
    },
    { 
        field: 'retiros', 
        headerName: 'Retiros', 
        valueFormatter: formatCurrency , 
        cellClass: (params) => params.value ? 'withdrawal-cell' : '',
    },
    { 
        field: 'saldo', 
        headerName: 'Saldo', 
        valueFormatter: formatCurrency , 
        cellClass: (params) => params.value ? 'balance-cell' : '',
    },
    { field: 'r', headerName: 'Rubro', filter: true, sortable: true }, 
    { field: 'partida', headerName: 'Partida', filter: true, sortable: true  },
    { field: 'fecha_factura', headerName: 'Fecha de factura', filter: true, sortable: true  },
    { field: 'folio_fiscal', headerName: 'Folio fiscal', filter: true, sortable: true  },
    { field: 'tipo_adjudicacion', headerName: 'Tipo de adjudicación', filter: true, sortable: true  },
    { field: 'num_adj_contrato', headerName: 'Numero de adjudicación o contrato', filter: true, sortable: true  },
    { field: 'num_suficiencia_presupuestal', headerName: 'Numero de suficiencia presupuestal', filter: true, sortable: true  },
    { field: 'orden_servicio_compra', headerName: 'Orden de servicio o compra', filter: true, sortable: true  },
    { field: 'clc', headerName: 'CLC', filter: true, sortable: true  },
    { field: 'poliza', headerName: 'Poliza', filter: true, sortable: true  },
    { field: 'numero_cuenta_proovedor', headerName: 'Numero de cuenta de proveedor', filter: true, sortable: true  },
    { field: 'referencia_bancaria', headerName: 'Referencia bancaria', filter: true, sortable: true  },
    { field: 'clue', headerName: 'CLUE', filter: true, sortable: true  },
    { field: 'nombre_clue', headerName: 'Aplica en', filter: true, sortable: true  },
    { field: 'nombrepartida', headerName: 'Nombre de la partida', filter: true, sortable: true  },
    { field: 'mes_servicio', headerName: 'Mes de servicio' },
    { field: 'ejercicio', headerName: 'Ejercicio' },
]

export const S200=[
    { field: 'fechas', headerName: 'Fecha', filter: true, sortable: true },
    { field: 'mes', headerName: 'Mes', filter: true, sortable: true },
    { field: 'metodo_pago', headerName: 'metodo de pago', filter: true, sortable: true },
    { field: 'rfc', headerName: 'RFC', filter: true, sortable: true },
    { field: 'proveedor', headerName: 'Proveedor', filter: true, sortable: true  },
    { field: 'factura', headerName: 'Factura', filter: true, sortable: true  },
    { 
        field: 'parcial', 
        headerName: 'Parcial', 
        valueFormatter: formatCurrency , 
        cellClass: (params) => params.value ? 'partial-cell' : '',
    },
    { 
        field: 'retiros', 
        headerName: 'Retiros', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'withdrawal-cell' : '',
    },
    { 
        field: 'depositos', 
        headerName: 'Depositos', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'deposit-cell' : '',
    },
    { 
        field: 'saldo', 
        headerName: 'Saldo', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'balance-cell' : '',
    },
    { field: 'r', headerName: 'Rubro', filter: true, sortable: true }, 
    { field: 'partida', headerName: 'Partida', filter: true, sortable: true  },
    { field: 'fecha_factura', headerName: 'Fecha de factura', filter: true, sortable: true  },
    { field: 'folio_fiscal', headerName: 'Folio fiscal', filter: true, sortable: true  },
    { field: 'tipo_adjudicacion', headerName: 'Tipo de adjudicación', filter: true, sortable: true  },
    { field: 'num_adj_contrato', headerName: 'Numero de adjudicación o contrato', filter: true, sortable: true  },
    { field: 'num_techo_financiero', headerName: 'Numero de techo financiero', filter: true, sortable: true  },
    { field: 'orden_servicio_compra', headerName: 'Orden de servicio o compra', filter: true, sortable: true  },
    { field: 'clc', headerName: 'CLC', filter: true, sortable: true  },
    { field: 'poliza', headerName: 'Poliza', filter: true, sortable: true  },
    { field: 'numero_cuenta_proovedor', headerName: 'Numero de cuenta de proveedor', filter: true, sortable: true  },
    { field: 'referencia_bancaria', headerName: 'Referencia bancaria', filter: true, sortable: true  },
    { field: 'clue', headerName: 'CLUE', filter: true, sortable: true  },
    { field: 'nombre_clue', headerName: 'Aplica en', filter: true, sortable: true  },
    { field: 'nombrepartida', headerName: 'Nombre partida' },
    { field: 'mes_servicio', headerName: 'Mes de servicio' },
    { field: 'ejercicio', headerName: 'Ejercicio' },
]

export const SaNAS=[
    { field: 'fechas', headerName: 'Fecha', filter: true, sortable: true },
    { field: 'mes', headerName: 'Mes', filter: true, sortable: true },
    { field: 'forma_pago', headerName: 'Forma de pago', filter: true, sortable: true },
    { field: 'metodo_pago', headerName: 'Metodo de pago', filter: true, sortable: true },
    { field: 'rfc', headerName: 'RFC', filter: true, sortable: true },
    { field: 'proveedor', headerName: 'Proveedor', filter: true, sortable: true  },
    { field: 'factura', headerName: 'Factura', filter: true, sortable: true  },
    { 
        field: 'parcial', 
        headerName: 'Parcial', 
        valueFormatter: formatCurrency , 
        cellClass: (params) => params.value ? 'partial-cell' : '',
    },
    { 
        field: 'depositos', 
        headerName: 'Depositos', 
        valueFormatter: formatCurrency , 
        cellClass: (params) => params.value ? 'deposit-cell' : '',
    },
    { 
        field: 'retiros', 
        headerName: 'Retiros', 
        valueFormatter: formatCurrency , 
        cellClass: (params) => params.value ? 'withdrawal-cell' : '',
    },
    { 
        field: 'saldo', 
        headerName: 'Saldo', 
        valueFormatter: formatCurrency , 
        cellClass: (params) => params.value ? 'balance-cell' : '',
    },
    { field: 'r', headerName: 'Rubro', filter: true, sortable: true }, 
    { field: 'partida', headerName: 'Partida', filter: true, sortable: true  },
    { field: 'fecha_factura', headerName: 'Fecha de factura', filter: true, sortable: true  },
    { field: 'folio_fiscal', headerName: 'Folio fiscal', filter: true, sortable: true  },
    { field: 'tipo_adjudicacion', headerName: 'Tipo de adjudicación', filter: true, sortable: true  },
    { field: 'num_adj_contrato', headerName: 'Numero de adjudicación o contrato', filter: true, sortable: true  },
    { field: 'num_techo_financiero', headerName: 'Numero de techo financiero', filter: true, sortable: true  },
    //{ field: 'num_suficiencia_presupuestal', headerName: 'Numero de suficiencia presupuestal', filter: true, sortable: true  },
    { field: 'orden_servicio_compra', headerName: 'Orden de servicio o compra', filter: true, sortable: true  },
    { field: 'clc', headerName: 'CLC', filter: true, sortable: true  },
    { field: 'poliza', headerName: 'Poliza', filter: true, sortable: true  },
    { field: 'numero_cuenta_proovedor', headerName: 'Numero de cuenta de proveedor', filter: true, sortable: true  },
    { field: 'referencia_bancaria', headerName: 'Referencia bancaria', filter: true, sortable: true  },
    { field: 'clue', headerName: 'CLUE', filter: true, sortable: true  },
    { field: 'nombre_clue', headerName: 'Aplica en', filter: true, sortable: true  },
    { field: 'nombrepartida', headerName: 'Nombre de la partida', filter: true, sortable: true  },
    { field: 'mes_servicio', headerName: 'Mes de servicio' },
    { field: 'ejercicio', headerName: 'Ejercicio' },
]

export const ALE=[
    { field: 'fechas', headerName: 'Fecha', filter: true, sortable: true },
    { field: 'mes', headerName: 'Mes', filter: true, sortable: true },
    { field: 'metodo_pago', headerName: 'Metodo de pago', filter: true, sortable: true },
    { field: 'rfc', headerName: 'RFC', filter: true, sortable: true },
    { field: 'proveedor', headerName: 'Proveedor', filter: true, sortable: true  },
    { field: 'factura', headerName: 'Factura', filter: true, sortable: true  },
    { 
        field: 'parcial', 
        headerName: 'Parcial', 
        valueFormatter: formatCurrency , 
        cellClass: (params) => params.value ? 'partial-cell' : '',
    },
    { 
        field: 'depositos', 
        headerName: 'Depositos', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'deposit-cell' : '',
    },
    { 
        field: 'retiros', 
        headerName: 'Retiros', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'withdrawal-cell' : '',
    },
    { 
        field: 'saldo', 
        headerName: 'Saldo', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'balance-cell' : '',
    },
    { field: 'r', headerName: 'Rubro', filter: true, sortable: true }, 
    { field: 'partida', headerName: 'Partida', filter: true, sortable: true  },
    { field: 'fecha_factura', headerName: 'Fecha de factura', filter: true, sortable: true  },
    { field: 'folio_fiscal', headerName: 'Folio fiscal', filter: true, sortable: true  },
    { field: 'tipo_adjudicacion', headerName: 'Tipo de adjudicación', filter: true, sortable: true  },
    { field: 'num_adj_contrato', headerName: 'Numero de adjudicación o contrato', filter: true, sortable: true  },
    { field: 'num_techo_financiero', headerName: 'Numero de techo financiero', filter: true, sortable: true  },
    { field: 'orden_servicio_compra', headerName: 'Orden de servicio o compra', filter: true, sortable: true  },
    { field: 'clc', headerName: 'CLC', filter: true, sortable: true  },
    { field: 'poliza', headerName: 'Poliza', filter: true, sortable: true  },
    { field: 'numero_cuenta_proovedor', headerName: 'Numero de cuenta de proveedor', filter: true, sortable: true  },
    { field: 'referencia_bancaria', headerName: 'Referencia bancaria', filter: true, sortable: true  },
    { field: 'clue', headerName: 'CLUE', filter: true, sortable: true  },
    { field: 'nombre_clue', headerName: 'Aplica en', filter: true, sortable: true  },
    { field: 'nombrepartida', headerName: 'Nombre partida' },
    { field: 'mes_servicio', headerName: 'Mes de servicio' },
    { field: 'ejercicio', headerName: 'Ejercicio' },
]

export const E001=[
    { field: 'fechas', headerName: 'Fecha', filter: true, sortable: true },
    { field: 'mes', headerName: 'Mes', filter: true, sortable: true },
    { field: 'metodo_pago', headerName: 'metodo de pago', filter: true, sortable: true },
    { field: 'rfc', headerName: 'RFC', filter: true, sortable: true },
    { field: 'proveedor', headerName: 'Proveedor', filter: true, sortable: true  },
    { field: 'factura', headerName: 'Factura', filter: true, sortable: true  },
    { 
        field: 'parcial', 
        headerName: 'Parcial', 
        valueFormatter: formatCurrency , 
        cellClass: (params) => params.value ? 'partial-cell' : '',
    },
    { 
        field: 'retiros', 
        headerName: 'Retiros', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'withdrawal-cell' : '',
    },
    { 
        field: 'depositos', 
        headerName: 'Depositos', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'deposit-cell' : '',
    },
    { 
        field: 'saldo', 
        headerName: 'Saldo', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'balance-cell' : '',
    },
    { field: 'r', headerName: 'Rubro', filter: true, sortable: true }, 
    { field: 'partida', headerName: 'Partida', filter: true, sortable: true  },
    { field: 'fecha_factura', headerName: 'Fecha de factura', filter: true, sortable: true  },
    { field: 'folio_fiscal', headerName: 'Folio fiscal', filter: true, sortable: true  },
    { field: 'tipo_adjudicacion', headerName: 'Tipo de adjudicación', filter: true, sortable: true  },
    { field: 'num_adj_contrato', headerName: 'Numero de adjudicación o contrato', filter: true, sortable: true  },
    { field: 'num_techo_financiero', headerName: 'Numero de techo financiero', filter: true, sortable: true  },
    { field: 'orden_servicio_compra', headerName: 'Orden de servicio o compra', filter: true, sortable: true  },
    { field: 'clc', headerName: 'CLC', filter: true, sortable: true  },
    { field: 'poliza', headerName: 'Poliza', filter: true, sortable: true  },
    { field: 'numero_cuenta_proovedor', headerName: 'Numero de cuenta de proveedor', filter: true, sortable: true  },
    { field: 'referencia_bancaria', headerName: 'Referencia bancaria', filter: true, sortable: true  },
    { field: 'clue', headerName: 'CLUE', filter: true, sortable: true  },
    { field: 'nombre_clue', headerName: 'Aplica en', filter: true, sortable: true  },
    { field: 'nombrepartida', headerName: 'Nombre partida' },
    { field: 'mes_servicio', headerName: 'Mes de servicio' },
    { field: 'ejercicio', headerName: 'Ejercicio' },
]


function formatCurrency(params) {
    if (!params.value) return '';

    let number;

    // Verificar si el valor es un número o una cadena
    if (typeof params.value === 'number') {
        number = params.value;
    } else if (typeof params.value === 'string') {
        // Remover caracteres no numéricos si es una cadena
        number = parseFloat(params.value.replace(/[^0-9.-]+/g, ''));
    }

    if (isNaN(number)) return ''; // Si no es un número válido, retornar una cadena vacía

    // Formatear como moneda en MXN
    return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(number);
}


export const U013Editable=[
    { field: 'fechas', headerName: 'Fecha', filter: true, sortable: true, editable: true },
    { field: 'mes', headerName: 'Mes', filter: true, sortable: true, editable: true },
    { field: 'forma_pago', headerName: 'Forma de pago', filter: true, sortable: true, editable: true },
    { field: 'metodo_pago', headerName: 'Metodo de pago', filter: true, sortable: true, editable: true },
    { field: 'rfc', headerName: 'RFC', filter: true, sortable: true, editable: true },
    { field: 'proveedor', headerName: 'Proveedor', filter: true, sortable: true, editable: true  },
    { field: 'factura', headerName: 'Factura', filter: true, sortable: true, editable: true  },
    { 
        field: 'parcial', 
        headerName: 'Parcial', 
        valueFormatter: formatCurrency , 
        cellClass: (params) => params.value ? 'partial-cell' : '',
        editable: true,
    },
    { 
        field: 'depositos', 
        headerName: 'Depositos', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'deposit-cell' : '',
        editable: true,
    },
    { 
        field: 'retiros', 
        headerName: 'Retiros', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'withdrawal-cell' : '',
        editable: true,
    },
    { 
        field: 'saldo', 
        headerName: 'Saldo', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'balance-cell' : '',
        editable: true,
    },
    { field: 'r', headerName: 'Rubro', filter: true, sortable: true, editable: true }, 
    { field: 'partida', headerName: 'Partida', filter: true, sortable: true, editable: true  },
    { field: 'fecha_factura', headerName: 'Fecha de factura', filter: true, sortable: true, editable: true  },
    { field: 'folio_fiscal', headerName: 'Folio fiscal', filter: true, sortable: true, editable: true  },
    { field: 'tipo_adjudicacion', headerName: 'Tipo de adjudicación', filter: true, sortable: true, editable: true  },
    { field: 'num_adj_contrato', headerName: 'Numero de adjudicación o contrato', filter: true, sortable: true, editable: true  },
    { field: 'num_suficiencia_presupuestal', headerName: 'Numero de suficiencia presupuestal', filter: true, sortable: true, editable: true  },
    { field: 'orden_servicio_compra', headerName: 'Orden de servicio o compra', filter: true, sortable: true, editable: true  },
    { field: 'clc', headerName: 'CLC', filter: true, sortable: true, editable: true  },
    { field: 'poliza', headerName: 'Poliza', filter: true, sortable: true  },
    { field: 'numero_cuenta_proovedor', headerName: 'Numero de cuenta de proveedor', filter: true, sortable: true, editable: true  },
    { field: 'referencia_bancaria', headerName: 'Referencia bancaria', filter: true, sortable: true, editable: true  },
    { field: 'clue', headerName: 'CLUE', filter: true, sortable: true, editable: true  },
    { field: 'nombre_clue', headerName: 'Aplica en', filter: true, sortable: true, editable: true  },
    { field: 'nombrepartida', headerName: 'Nombre de la partida', filter: true, sortable: true, editable: true  },
    { field: 'mes_servicio', headerName: 'Mes de servicio', editable: true },
    { field: 'ejercicio', headerName: 'Ejercicio', editable: true },
]

export const S200Editable=[
    { field: 'fechas', headerName: 'Fecha', filter: true, sortable: true, editable: true },
    { field: 'mes', headerName: 'Mes', filter: true, sortable: true, editable: true },
    { field: 'metodo_pago', headerName: 'metodo de pago', filter: true, sortable: true, editable: true },
    { field: 'rfc', headerName: 'RFC', filter: true, sortable: true, editable: true },
    { field: 'proveedor', headerName: 'Proveedor', filter: true, sortable: true, editable: true  },
    { field: 'factura', headerName: 'Factura', filter: true, sortable: true, editable: true },
    { 
        field: 'parcial', 
        headerName: 'Parcial', 
        valueFormatter: formatCurrency , 
        cellClass: (params) => params.value ? 'partial-cell' : '',
        editable: true,
    },
    { 
        field: 'retiros', 
        headerName: 'Retiros', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'withdrawal-cell' : '',
        editable: true,
    },
    { 
        field: 'depositos', 
        headerName: 'Depositos', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'deposit-cell' : '',
        editable: true,
    },
    { 
        field: 'saldo', 
        headerName: 'Saldo', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'balance-cell' : '',
        editable: true,
    },
    { field: 'r', headerName: 'Rubro', filter: true, sortable: true, editable: true }, 
    { field: 'partida', headerName: 'Partida', filter: true, sortable: true, editable: true },
    { field: 'fecha_factura', headerName: 'Fecha de factura', filter: true, sortable: true, editable: true },
    { field: 'folio_fiscal', headerName: 'Folio fiscal', filter: true, sortable: true, editable: true },
    { field: 'tipo_adjudicacion', headerName: 'Tipo de adjudicación', filter: true, sortable: true, editable: true },
    { field: 'num_adj_contrato', headerName: 'Numero de adjudicación o contrato', filter: true, sortable: true, editable: true },
    { field: 'num_techo_financiero', headerName: 'Numero de techo financiero', filter: true, sortable: true, editable: true },
    { field: 'orden_servicio_compra', headerName: 'Orden de servicio o compra', filter: true, sortable: true, editable: true },
    { field: 'clc', headerName: 'CLC', filter: true, sortable: true, editable: true },
    { field: 'poliza', headerName: 'Poliza', filter: true, sortable: true, editable: true },
    { field: 'numero_cuenta_proovedor', headerName: 'Numero de cuenta de proveedor', filter: true, sortable: true, editable: true },
    { field: 'referencia_bancaria', headerName: 'Referencia bancaria', filter: true, sortable: true, editable: true },
    { field: 'clue', headerName: 'CLUE', filter: true, sortable: true, editable: true },
    { field: 'nombre_clue', headerName: 'Aplica en', filter: true, sortable: true, editable: true },
    { field: 'nombrepartida', headerName: 'Nombre partida', editable: true },
    { field: 'mes_servicio', headerName: 'Mes de servicio', editable: true },
    { field: 'ejercicio', headerName: 'Ejercicio', editable: true },
]


export const SaNASEditable=[
    { field: 'fechas', headerName: 'Fecha', filter: true, sortable: true, editable: true },
    { field: 'mes', headerName: 'Mes', filter: true, sortable: true, editable: true },
    { field: 'forma_pago', headerName: 'Forma de pago', filter: true, sortable: true, editable: true },
    { field: 'metodo_pago', headerName: 'Metodo de pago', filter: true, sortable: true, editable: true },
    { field: 'rfc', headerName: 'RFC', filter: true, sortable: true, editable: true },
    { field: 'proveedor', headerName: 'Proveedor', filter: true, sortable: true, editable: true  },
    { field: 'factura', headerName: 'Factura', filter: true, sortable: true, editable: true  },
    { 
        field: 'parcial', 
        headerName: 'Parcial', 
        valueFormatter: formatCurrency , 
        cellClass: (params) => params.value ? 'partial-cell' : '',
        editable: true,
    },
    { 
        field: 'depositos', 
        headerName: 'Depositos', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'deposit-cell' : '',
        editable: true,
    },
    { 
        field: 'retiros', 
        headerName: 'Retiros', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'withdrawal-cell' : '',
        editable: true,
    },
    { 
        field: 'saldo', 
        headerName: 'Saldo', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'balance-cell' : '',
        editable: true,
    },
    { field: 'r', headerName: 'Rubro', filter: true, sortable: true, editable: true }, 
    { field: 'partida', headerName: 'Partida', filter: true, sortable: true, editable: true  },
    { field: 'fecha_factura', headerName: 'Fecha de factura', filter: true, sortable: true, editable: true  },
    { field: 'folio_fiscal', headerName: 'Folio fiscal', filter: true, sortable: true, editable: true  },
    { field: 'tipo_adjudicacion', headerName: 'Tipo de adjudicación', filter: true, sortable: true, editable: true  },
    { field: 'num_adj_contrato', headerName: 'Numero de adjudicación o contrato', filter: true, sortable: true, editable: true  },
    { field: 'num_techo_financiero', headerName: 'Numero de techo financiero', filter: true, sortable: true, editable: true  },
    //{ field: 'num_suficiencia_presupuestal', headerName: 'Numero de suficiencia presupuestal', filter: true, sortable: true, editable: true  },
    { field: 'orden_servicio_compra', headerName: 'Orden de servicio o compra', filter: true, sortable: true, editable: true  },
    { field: 'clc', headerName: 'CLC', filter: true, sortable: true, editable: true  },
    { field: 'poliza', headerName: 'Poliza', filter: true, sortable: true  },
    { field: 'numero_cuenta_proovedor', headerName: 'Numero de cuenta de proveedor', filter: true, sortable: true, editable: true  },
    { field: 'referencia_bancaria', headerName: 'Referencia bancaria', filter: true, sortable: true, editable: true  },
    { field: 'clue', headerName: 'CLUE', filter: true, sortable: true, editable: true  },
    { field: 'nombre_clue', headerName: 'Aplica en', filter: true, sortable: true, editable: true  },
    { field: 'nombrepartida', headerName: 'Nombre de la partida', filter: true, sortable: true, editable: true  },
    { field: 'mes_servicio', headerName: 'Mes de servicio', editable: true },
    { field: 'ejercicio', headerName: 'Ejercicio', editable: true },
]


export const ALEEditable=[
    { field: 'fechas', headerName: 'Fecha', filter: true, sortable: true, editable: true },
    { field: 'mes', headerName: 'Mes', filter: true, sortable: true, editable: true },
    { field: 'metodo_pago', headerName: 'Metodo de pago', filter: true, sortable: true, editable: true },
    { field: 'rfc', headerName: 'RFC', filter: true, sortable: true, editable: true },
    { field: 'proveedor', headerName: 'Proveedor', filter: true, sortable: true, editable: true  },
    { field: 'factura', headerName: 'Factura', filter: true, sortable: true, editable: true },
    { 
        field: 'parcial', 
        headerName: 'Parcial', 
        valueFormatter: formatCurrency , 
        cellClass: (params) => params.value ? 'partial-cell' : '',
        editable: true,
    },
    { 
        field: 'depositos', 
        headerName: 'Depositos', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'deposit-cell' : '',
        editable: true,
    },
    { 
        field: 'retiros', 
        headerName: 'Retiros', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'withdrawal-cell' : '',
        editable: true,
    },
    { 
        field: 'saldo', 
        headerName: 'Saldo', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'balance-cell' : '',
        editable: true,
    },
    { field: 'r', headerName: 'Rubro', filter: true, sortable: true, editable: true }, 
    { field: 'partida', headerName: 'Partida', filter: true, sortable: true, editable: true },
    { field: 'fecha_factura', headerName: 'Fecha de factura', filter: true, sortable: true, editable: true },
    { field: 'folio_fiscal', headerName: 'Folio fiscal', filter: true, sortable: true, editable: true },
    { field: 'tipo_adjudicacion', headerName: 'Tipo de adjudicación', filter: true, sortable: true, editable: true },
    { field: 'num_adj_contrato', headerName: 'Numero de adjudicación o contrato', filter: true, sortable: true, editable: true },
    { field: 'num_techo_financiero', headerName: 'Numero de techo financiero', filter: true, sortable: true, editable: true },
    { field: 'orden_servicio_compra', headerName: 'Orden de servicio o compra', filter: true, sortable: true, editable: true },
    { field: 'clc', headerName: 'CLC', filter: true, sortable: true, editable: true },
    { field: 'poliza', headerName: 'Poliza', filter: true, sortable: true, editable: true },
    { field: 'numero_cuenta_proovedor', headerName: 'Numero de cuenta de proveedor', filter: true, sortable: true, editable: true },
    { field: 'referencia_bancaria', headerName: 'Referencia bancaria', filter: true, sortable: true, editable: true },
    { field: 'clue', headerName: 'CLUE', filter: true, sortable: true, editable: true },
    { field: 'nombre_clue', headerName: 'Aplica en', filter: true, sortable: true, editable: true },
    { field: 'nombrepartida', headerName: 'Nombre partida', editable: true },
    { field: 'mes_servicio', headerName: 'Mes de servicio', editable: true },
    { field: 'ejercicio', headerName: 'Ejercicio', editable: true },
]

export const E001Editable=[
    { field: 'fechas', headerName: 'Fecha', filter: true, sortable: true, editable: true },
    { field: 'mes', headerName: 'Mes', filter: true, sortable: true, editable: true },
    { field: 'metodo_pago', headerName: 'metodo de pago', filter: true, sortable: true, editable: true },
    { field: 'rfc', headerName: 'RFC', filter: true, sortable: true, editable: true },
    { field: 'proveedor', headerName: 'Proveedor', filter: true, sortable: true, editable: true  },
    { field: 'factura', headerName: 'Factura', filter: true, sortable: true, editable: true },
    { 
        field: 'parcial', 
        headerName: 'Parcial', 
        valueFormatter: formatCurrency , 
        cellClass: (params) => params.value ? 'partial-cell' : '',
        editable: true,
    },
    { 
        field: 'retiros', 
        headerName: 'Retiros', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'withdrawal-cell' : '',
        editable: true,
    },
    { 
        field: 'depositos', 
        headerName: 'Depositos', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'deposit-cell' : '',
        editable: true,
    },
    { 
        field: 'saldo', 
        headerName: 'Saldo', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'balance-cell' : '',
        editable: true,
    },
    { field: 'r', headerName: 'Rubro', filter: true, sortable: true, editable: true }, 
    { field: 'partida', headerName: 'Partida', filter: true, sortable: true, editable: true },
    { field: 'fecha_factura', headerName: 'Fecha de factura', filter: true, sortable: true, editable: true },
    { field: 'folio_fiscal', headerName: 'Folio fiscal', filter: true, sortable: true, editable: true },
    { field: 'tipo_adjudicacion', headerName: 'Tipo de adjudicación', filter: true, sortable: true, editable: true },
    { field: 'num_adj_contrato', headerName: 'Numero de adjudicación o contrato', filter: true, sortable: true, editable: true },
    { field: 'num_techo_financiero', headerName: 'Numero de techo financiero', filter: true, sortable: true, editable: true },
    { field: 'orden_servicio_compra', headerName: 'Orden de servicio o compra', filter: true, sortable: true, editable: true },
    { field: 'clc', headerName: 'CLC', filter: true, sortable: true, editable: true },
    { field: 'poliza', headerName: 'Poliza', filter: true, sortable: true, editable: true },
    { field: 'numero_cuenta_proovedor', headerName: 'Numero de cuenta de proveedor', filter: true, sortable: true, editable: true },
    { field: 'referencia_bancaria', headerName: 'Referencia bancaria', filter: true, sortable: true, editable: true },
    { field: 'clue', headerName: 'CLUE', filter: true, sortable: true, editable: true },
    { field: 'nombre_clue', headerName: 'Aplica en', filter: true, sortable: true, editable: true },
    { field: 'nombrepartida', headerName: 'Nombre partida', editable: true },
    { field: 'mes_servicio', headerName: 'Mes de servicio', editable: true },
    { field: 'ejercicio', headerName: 'Ejercicio', editable: true },
]