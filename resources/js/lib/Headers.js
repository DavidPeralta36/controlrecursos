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
        cellRenderer: 'customCellRenderer' 
    },
    { 
        field: 'depositos', 
        headerName: 'Depositos', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'deposit-cell' : '',
        cellRenderer: 'customCellRenderer' 
    },
    { 
        field: 'retiros', 
        headerName: 'Retiros', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'withdrawal-cell' : '',
        cellRenderer: 'customCellRenderer'
    },
    { 
        field: 'saldo', 
        headerName: 'Saldo', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'balance-cell' : '',
        cellRenderer: 'customCellRenderer' 
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
        cellRenderer: 'customCellRenderer' 
    },
    { 
        field: 'depositos', 
        headerName: 'Depositos', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'deposit-cell' : '',
        cellRenderer: 'customCellRenderer' 
    },
    { 
        field: 'retiros', 
        headerName: 'Retiros', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'withdrawal-cell' : '',
        cellRenderer: 'customCellRenderer'
    },
    { 
        field: 'saldo', 
        headerName: 'Saldo', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'balance-cell' : '',
        cellRenderer: 'customCellRenderer' 
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
        cellRenderer: 'customCellRenderer' 
    },
    { 
        field: 'retiros', 
        headerName: 'Retiros', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'withdrawal-cell' : '',
        cellRenderer: 'customCellRenderer'
    },
    { 
        field: 'depositos', 
        headerName: 'Depositos', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'deposit-cell' : '',
        cellRenderer: 'customCellRenderer' 
    },
    { 
        field: 'saldo', 
        headerName: 'Saldo', 
        valueFormatter: formatCurrency, 
        cellClass: (params) => params.value ? 'balance-cell' : '',
        cellRenderer: 'customCellRenderer' 
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
    
    const number = parseFloat(params.value.replace(/[^0-9.-]+/g, '')); 
    if (isNaN(number)) return ''; 

    return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(number); 
}

function customCellRenderer(params) {
  const value = params.valueFormatted ? params.valueFormatted : params.value;

  if (!value) {
    return '';
  }

  return `
    <span class="custom-cell">
      ${value}
    </span>
  `;
}