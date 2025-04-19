// Script para actualizar el saldo disponible después de un pago o egreso
function actualizarSaldoDisponible() {
    $.ajax({
        url: '/salon_belleza/finanzas/getSaldo',
        method: 'GET',
        dataType: 'json',
        success: function(resp) {
            if (resp.saldo !== undefined) {
                $('#saldoDisponible').text(parseFloat(resp.saldo).toFixed(2));
            }
        },
        error: function() {
            // Opcional: mostrar alerta de error
        }
    });
}
