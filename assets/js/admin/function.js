// USUARIOS

// --- RESUMEN PREVIO DE PAGO Y PRÉSTAMOS ---
$(document).on('click', '#btnResumenPago', function() {
    let empleadoId = $('#empleado-id').val();
    if (!empleadoId) {
        swal('Seleccione un empleado primero', '', 'warning');
        return;
    }
    $.ajax({
        url: window.BASE_URL + "pagos_empleados/getResumenPago/" + empleadoId,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            // Mostrar resumen previo en el modal
            console.log(response); // Para depuración
            $('#total-pagar').text(parseFloat(response.total_servicios).toLocaleString('es-CO', {minimumFractionDigits:2}));
            if (response.total_prestamos_descontados > 0) {
                $('#total-prestamos').show();
                $('#detalle-prestamos').show();
                $('#prestamos-descontados').text(parseFloat(response.total_prestamos_descontados).toLocaleString('es-CO', {minimumFractionDigits:2}));
                let tbody = $('#tbody-detalle-prestamos');
                tbody.empty();
                response.detalle_prestamos.forEach(function(prestamo) {
                    tbody.append('<tr><td>$' + parseFloat(prestamo.monto).toLocaleString('es-CO', {minimumFractionDigits:2}) + '</td><td>' + prestamo.fecha + '</td></tr>');
                });
            } else {
                $('#total-prestamos').hide();
                $('#detalle-prestamos').hide();
            }
            $('#neto-pagar').show();
            let neto = Number(response.pago_neto);
            $('#neto-pago').text(!isNaN(neto) ? neto.toLocaleString('es-CO', {minimumFractionDigits:2}) : '0.00');
            // Habilitar o deshabilitar el botón de pagar según el neto
            if (neto > 0) {
                $('#btnPagar').prop('disabled', false);
            } else {
                $('#btnPagar').prop('disabled', true);
            }
        },
        error: function() {
            swal('Error al obtener el resumen de pago', '', 'error');
        }
    });
});

// --- PAGO DE EMPLEADO Y DESGLOSE DE PRÉSTAMOS ---
$(document).on('click', '#btnPagar', function() {
    let empleadoId = $('#empleado-id').val();
    if (!empleadoId) {
        swal('Seleccione un empleado primero', '', 'warning');
        return;
    }
    $.ajax({
        url: window.BASE_URL + "pagos_empleados/pagarEmpleado/" + empleadoId,
        method: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.success === false) {
                swal('Error', response.message, 'error');
                $('#btnPagar').prop('disabled', true);
                return;
            }
            // Mostrar préstamos descontados si existen
            if (response.prestamos_descontados && response.prestamos_descontados.length > 0) {
                $('#total-prestamos').show();
                $('#detalle-prestamos').show();
                $('#prestamos-descontados').text(parseFloat(response.total_prestamos_descontados).toLocaleString('es-CO', {minimumFractionDigits:2}));
                let tbody = $('#tbody-detalle-prestamos');
                tbody.empty();
                response.prestamos_descontados.forEach(function(prestamo) {
                    tbody.append('<tr><td>$' + parseFloat(prestamo.monto).toLocaleString('es-CO', {minimumFractionDigits:2}) + '</td><td>' + prestamo.fecha + '</td></tr>');
                });
            } else {
                $('#total-prestamos').hide();
                $('#detalle-prestamos').hide();
            }
            // Mostrar neto a pagar
            $('#neto-pagar').show();
            $('#neto-pago').text(parseFloat(response.neto_pagar).toLocaleString('es-CO', {minimumFractionDigits:2}));
            swal('Pago realizado con éxito', '', 'success');
            $('#btnPagar').prop('disabled', true); // Deshabilitar después de pagar
            actualizarSaldoDisponible();
            if (window.pagoempleado && typeof window.pagoempleado.ajax === 'function') {
                window.pagoempleado.ajax.reload(null, false);
            }
        },
        error: function() {
            swal('Error al procesar el pago', '', 'error');
        }
    });
});

// --- HISTORIAL DE PRÉSTAMOS EN EGRESOS ---
// --- VER PAGOS DE EMPLEADO ---
$(document).on('click', '.btn-ver-pagos', function() {
    let empleadoId = $(this).data('empleado');
    $.ajax({
        url: window.BASE_URL + "pagos_empleados/getPagosEmpleado/" + empleadoId,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            let tbody = $('#tbody-pagos');
            tbody.empty();
            if (response.pagos && response.pagos.length > 0) {
                response.pagos.forEach(function(pago) {
                    tbody.append('<tr><td>$' + parseFloat(pago.pago).toFixed(2) + '</td><td>' + pago.fecha_pago + '</td></tr>');
                });
            } else {
                tbody.append('<tr><td colspan="2" class="text-center">Sin pagos registrados</td></tr>');
            }
            $('#modalPagos').modal('show');
        },
        error: function() {
            swal('Error al cargar los pagos', '', 'error');
        }
    });
});

// Delegación de eventos para asegurar funcionamiento en modales dinámicos
$(document).on('click', '#btnVerPrestamos', function() {
    let empleadoId = $('#empleado_id').val();
    console.log("Empleado seleccionado:", empleadoId);
    if (!empleadoId) {
        swal('Seleccione un empleado primero', '', 'warning');
        return;
    }
    $.ajax({
        url: window.BASE_URL + "pagos_empleados/getPrestamosEmpleado/" + empleadoId,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log("Respuesta AJAX:", response);
            let tbody = $('#tbody-prestamos-empleado');
            tbody.empty();
            if (response.prestamos && response.prestamos.length > 0) {
                response.prestamos.forEach(function(prestamo) {
                    let estadoBadge = prestamo.estado === 'saldado' ? '<span class="badge bg-success">Saldado</span>' : '<span class="badge bg-warning text-dark">Pendiente</span>';
                    tbody.append('<tr>' +
                        '<td>$' + parseFloat(prestamo.monto).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}) + '</td>' +
                        '<td>' + estadoBadge + '</td>' +
                        '<td>' + prestamo.fecha + '</td>' +
                        '</tr>');
                });
            } else {
                tbody.append('<tr><td colspan="3" class="text-center">Sin préstamos registrados</td></tr>');
            }
            let modal = new bootstrap.Modal(document.getElementById('modalPrestamosEmpleado'));
            modal.show();
        },
        error: function() {
            swal('Error al cargar el historial de préstamos', '', 'error');
        }
    });
});

const tableUsuarios = new DataTable("#tableUsuarios", {
  columnDefs: [
    { className: "text-center", targets: [7] },
    { className: "text-left", targets: [0, 1, 2, 3, 4, 5, 6] },
  ],
  ajax: {
    url: window.BASE_URL + "/getUsers",
    dataSrc: "",
  },
  columns: [
    { data: "nombre" },
    { data: "apellidos" },
    { data: "cedula" },
    { data: "telefono" },
    { data: "direccion" },
    { data: "correo" },
    { data: "rol" },
    { data: "accion" },
  ],
  responsive: true,
  bDestroy: true,
  iDisplayLength: 10,
  order: [[0, "desc"]],
});

function ModalUsuario() {
  document.querySelector("#frmUsuario").reset();
  document.querySelector("#idUsuario").value = "";
  document.querySelector("#titleModal").innerHTML = "Nuevo Usuario";
  document.querySelector("#btnGuardarUsuario").innerHTML = "Guardar";
  $("#modelUsuario").modal("show");
}
// insertar y actualizar usuario
document.addEventListener("DOMContentLoaded", function () {
  $("#frmUsuario").on("submit", function (event) {
    event.preventDefault(); // Prevenir el envío del formulario por defecto

    let base_url = window.BASE_URL;
    let formData = new FormData(this);
    let idUsuario = $("#idUsuario").val();
    let url =
      base_url + (idUsuario ? "usuarios/updateUsers" : "usuarios/setUsers");

    $.ajax({
      type: "POST",
      url: url,
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        if (response.ok) {
          swal({
            title: "Success",
            text: response.post,
            icon: "success",
            button: "OK",
          });
          // Recargar la tabla de Clientes o redirigir al dashboard
          tableUsuarios.ajax.reload(null, false);
          document.querySelector("#frmUsuario").reset();
          $("#modelUsuario").modal("hide");
        } else {
          swal({
            title: "Error",
            text: response.post,
            icon: "error",
            button: "OK",
          });
        }
      },
    });
  });
});

//editar usuario
function editarUsuario(id) {
  document
    .querySelector(".modal-header")
    .classList.replace("headerRegister", "headerUpdate");
  document
    .querySelector("#btnGuardarUsuario")
    .classList.replace("btn-primary", "btn-info");
  document.querySelector("#btnGuardarUsuario").innerHTML = "Actualizar";
  document.querySelector("#titleModal").innerHTML = "Actualizar Usuario";
  document.querySelector("#frmUsuario").reset();

  let base_url = window.BASE_URL;
  $.ajax({
    url: base_url + "usuarios/getUser/" + id,
    type: "GET",
    dataType: "json",
    success: function (resp) {
      $("#idUsuario").val(resp.id);
      $("#nombre").val(resp.nombre);
      $("#apellidos").val(resp.apellidos);
      $("#cedula").val(resp.cedula);
      $("#telefono").val(resp.telefono);
      $("#direccion").val(resp.direccion);
      $("#correo").val(resp.correo);
      $("#clave").val(resp.clave);
      // Asignar valor al select de rol
      $("#rol").val(resp.rol).change(); // Asegúrate de que la opción correcta esté seleccionada
      // Asignar valor al select de estado
      $("#modelUsuario").modal("show");
    },
    error: function () {
      swal({
        title: "Error",
        text: "No se pudo obtener la información del Empleado",
        icon: "error",
        button: "OK",
      });
    },
  });
}

// eliminar el usuario
function eliminarUsuario($id) {
  swal({
    title: "¿Estás seguro?",
    text: "El Usuario será eliminado y no podrá acceder al sistema.",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      let base_url = window.BASE_URL;
      $.ajax({
        url: base_url + "usuarios/deleteUsers/" + $id,
        type: "POST",
        dataType: "json",
        success: function (response) {
          if (response.ok == true) {
            swal({
              title: "Eliminar el Usuario",
              text: response.post,
              icon: "success",
              button: "OK",
            });
            tableUsuarios.ajax.reload(null, false);
          } else {
            swal({
              title: "No fue posible eliminar el Usuario",
              text: response.post,
              icon: "error",
              button: "OK",
            });
          }
        },
        error: function (xhr, status, error) {
          swal(
            "Error",
            "No se pudo eliminar el Usuario. Intente nuevamente.",
            "error"
          );
        },
      });
    }
  });
}
// permisos para usuarios
function gestionarPermisos(id) {
  document
    .querySelector(".modal-header")
    .classList.replace("headerRegister", "headerUpdate");
  document
    .querySelector("#btnActionForm")
    .classList.replace("btn-primary", "btn-warning");
  document.querySelector("#btnActionForm").innerHTML = "Actualizar Permisos";
  document.querySelector("#titleModal").innerHTML = "Gestionar Permisos";
  document.querySelector("#frmUsuarios").reset();

  let base_url = window.BASE_URL;
  $.ajax({
    url: base_url + "usuarios/obtenerUsuario/" + id,
    type: "GET",
    dataType: "json",
    success: function (resp) {
      $("#idUsuario").val(resp.id);
      // Lógica adicional para mostrar los permisos del usuario en el modal
      // Aquí puedes agregar inputs o checkboxes para los permisos

      $("#ModalUsuarios").modal("show");
    },
  });
}

// PERMISOS
function openModalPermisos(usuarioId) {
  cargarModulos(usuarioId);
  $("#id_usuario").val(usuarioId);
  $("#ModalPermisos").modal("show");
}
// mostrar los modulos
function cargarModulos(usuarioId) {
  let base_url = window.BASE_URL;

  $.ajax({
    url: base_url + "usuarios/obtenerPermisos/" + usuarioId,
    type: "GET",
    dataType: "json",
    success: function (response) {
      let modulosDiv = $("#modulos");
      modulosDiv.empty();

      const colorClasses = [
        "color-1",
        "color-2",
        "color-3",
        "color-4",
        "color-5",
      ];

      // Verificar si colorClasses está definido y tiene elementos
      if (colorClasses.length === 0) {
        console.error("El array colorClasses está vacío.");
        return;
      }

      response.modulos.forEach((modulo, index) => {
        let checked = response.asignados.includes(modulo.id) ? "checked" : "";

        // Verificar si index es un número válido
        if (typeof index !== "number" || isNaN(index)) {
          console.error("El índice no es un número válido:", index);
          return;
        }

        // Asignar la clase de color basada en el índice
        let colorClass = colorClasses[index % colorClasses.length];

        modulosDiv.append(
          `<div class="col-md-4 text-center text-capitalize p-2 ${colorClass}">
                      <label for="modulo_${modulo.id}">${modulo.permiso}</label><br>
                      <input type="checkbox" id="modulo_${modulo.id}" name="permisos[]" value="${modulo.id}" ${checked}>
                  </div>`
        );
      });
    },
    error: function (xhr, status, error) {
      console.error("Error fetching permissions:", error);
    },
  });
}

// guardar los permisos asignados
function guardarPermisos(event) {
  event.preventDefault();
  let formData = new FormData(document.getElementById("frmPermisos"));
  let base_url = window.BASE_URL;

  $.ajax({
    url: base_url + "usuarios/guardarPermisos",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      if (response.ok) {
        swal({
          title: "Permisos",
          text: response.post,
          icon: "success",
          button: "OK",
        });
        $("#ModalPermisos").modal("hide");
      } else {
        swal({
          title: "Permisos",
          text: response.post,
          icon: "error",
          button: "OK",
        });
      }
    },
  });
}

// CLIENTES

const tableClientes = new DataTable("#tableClientes", {
  columnDefs: [
    { className: "text-center", targets: [5] },
    { className: "text-left", targets: [0, 1, 2, 3, 4] },
  ],
  ajax: {
    url: window.BASE_URL + "getClients",
    dataSrc: "",
  },
  columns: [
    { data: "nombre" },
    { data: "apellidos" },
    { data: "cedula" },
    { data: "telefono" },
    { data: "direccion" },
    { data: "accion" },
  ],
  responsive: true,
  bDestroy: true,
  iDisplayLength: 10,
  order: [[0, "desc"]],
});

function ModalClientes() {
  document.querySelector("#frmCliente").reset();
  document.querySelector("#idCliente").value = "";
  document.querySelector("#titleModal").innerHTML = "Nuevo Cliente";
  document.querySelector("#btnGuardarCliente").innerHTML = "Guardar";
  $("#modelCliente").modal("show");
}
// insertar y actualizar cliente
document.addEventListener("DOMContentLoaded", function () {
  $("#frmCliente").on("submit", function (event) {
    event.preventDefault(); // Prevenir el envío del formulario por defecto

    let base_url = window.BASE_URL;
    let formData = new FormData(this);
    let idCliente = $("#idCliente").val();
    let url =
      base_url + (idCliente ? "clientes/updateClient" : "clientes/setClient");

    $.ajax({
      type: "POST",
      url: url,
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        if (response.ok) {
          swal({
            title: "Success",
            text: response.post,
            icon: "success",
            button: "OK",
          });
          // Recargar la tabla de Clientes o redirigir al dashboard
          tableClientes.ajax.reload(null, false);
          document.querySelector("#frmCliente").reset();
          $("#modelCliente").modal("hide");
        } else {
          swal({
            title: "Error",
            text: response.post,
            icon: "error",
            button: "OK",
          });
        }
      },
    });
  });
});

//editar cliente
function editarCliente(id) {
  document
    .querySelector(".modal-header")
    .classList.replace("headerRegister", "headerUpdate");
  document
    .querySelector("#btnGuardarCliente")
    .classList.replace("btn-primary", "btn-info");
  document.querySelector("#btnGuardarCliente").innerHTML = "Actualizar";
  document.querySelector("#titleModal").innerHTML = "Actualizar Cliente";
  document.querySelector("#frmCliente").reset();

  let base_url = window.BASE_URL;
  $.ajax({
    url: base_url + "clientes/getClient/" + id,
    type: "GET",
    dataType: "json",
    success: function (resp) {
      if(resp.nombre == "GENERICO") {
          // Deshabilitar los campos
          $("#idCliente").val(resp.id);
          $("#nombre").prop("disabled", true);
          $("#apellidos").prop("disabled", true);
          $("#cedula").prop("disabled", true);
          $("#telefono").prop("disabled", true);
          $("#direccion").val(resp.direccion);

      } else {
          // Habilitar los campos si no es "GENERICO"
          $("#idCliente").prop("disabled", false);
          $("#nombre").prop("disabled", false);
          $("#apellidos").prop("disabled", false);
          $("#cedula").prop("disabled", false);
          $("#telefono").prop("disabled", false);
          $("#direccion").prop("disabled", false);
      }
      $("#idCliente").val(resp.id);
      $("#nombre").val(resp.nombre);
      $("#apellidos").val(resp.apellidos);
      $("#cedula").val(resp.cedula);
      $("#telefono").val(resp.telefono);
      $("#direccion").val(resp.direccion);
      $("#modelCliente").modal("show");
  },
  
    error: function () {
      swal({
        title: "Error",
        text: "No se pudo obtener la información del Cliente",
        icon: "error",
        button: "OK",
      });
    },
  });
}

// eliminar el cliente
function eliminarCliente($id) {
  swal({
    title: "¿Estás seguro?",
    text: "El Clientes será eliminado y no podrá acceder al sistema.",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      let base_url = window.BASE_URL;
      $.ajax({
        url: base_url + "clientes/deleteClient/" + $id,
        type: "POST",
        dataType: "json",
        success: function (response) {
          if (response.ok == true) {
            swal({
              title: "Eliminar el Clientes",
              text: response.post,
              icon: "success",
              button: "OK",
            });
            tableClientes.ajax.reload(null, false);
          } else {
            swal({
              title: "No fue posible eliminar el Clientes",
              text: response.post,
              icon: "error",
              button: "OK",
            });
          }
        },
        error: function (xhr, status, error) {
          swal(
            "Error",
            "No se pudo eliminar el Clientes. Intente nuevamente.",
            "error"
          );
        },
      });
    }
  });
}

// EMPLEADOS

const tableEmpleados = new DataTable("#tableEmpleados", {
  columnDefs: [
    { className: "text-center", targets: [5] },
    { className: "text-left", targets: [0, 1, 2, 3, 4] },
  ],
  ajax: {
    url: window.BASE_URL + "getEmpleados",
    dataSrc: "",
  },
  columns: [
    { data: "nombre" },
    { data: "apellidos" },
    { data: "cedula" },
    { data: "telefono" },
    { data: "direccion" },
    { data: "accion" },
  ],
  responsive: true,
  bDestroy: true,
  iDisplayLength: 10,
  order: [[0, "desc"]],
});

function ModalEmpleado() {
  document.querySelector("#frmEmpleado").reset();
  document.querySelector("#idEmpleado").value = "";
  document.querySelector("#titleModal").innerHTML = "Nuevo Empleado";
  document.querySelector("#btnGuardarEmpleado").innerHTML = "Guardar";
  $("#modelEmpleado").modal("show");
}
// insertar y actualizar usuario
document.addEventListener("DOMContentLoaded", function () {
  $("#frmEmpleado").on("submit", function (event) {
    event.preventDefault(); // Prevenir el envío del formulario por defecto

    let base_url = window.BASE_URL;
    let formData = new FormData(this);
    let idEmpleado = $("#idEmpleado").val();
    let url =
      base_url + (idEmpleado ? "empleado/updateEmpleado" : "empleado/setEmpleado");

    $.ajax({
      type: "POST",
      url: url,
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        if (response.ok) {
          swal({
            title: "Success",
            text: response.post,
            icon: "success",
            button: "OK",
          });
          // Recargar la tabla de Clientes o redirigir al dashboard
          tableEmpleados.ajax.reload(null, false);
          document.querySelector("#frmEmpleado").reset();
          $("#modelEmpleado").modal("hide");
        } else {
          swal({
            title: "Error",
            text: response.post,
            icon: "error",
            button: "OK",
          });
        }
      },
    });
  });
});

//editar usuario
function editarEmpleado(id) {
  document
    .querySelector(".modal-header")
    .classList.replace("headerRegister", "headerUpdate");
  document
    .querySelector("#btnGuardarEmpleado")
    .classList.replace("btn-primary", "btn-info");
  document.querySelector("#btnGuardarEmpleado").innerHTML = "Actualizar";
  document.querySelector("#titleModal").innerHTML = "Actualizar Empleado";
  document.querySelector("#frmEmpleado").reset();

  let base_url = window.BASE_URL;
  $.ajax({
    url: base_url + "empleado/getEmpleado/" + id,
    type: "GET",
    dataType: "json",
    success: function (resp) {
      $("#idEmpleado").val(resp.id);
      $("#nombre").val(resp.nombre);
      $("#apellidos").val(resp.apellidos);
      $("#cedula").val(resp.cedula);
      $("#telefono").val(resp.telefono);
      $("#direccion").val(resp.direccion);
      $("#pagado").val(resp.pagado);
      $("#modelEmpleado").modal("show");
    },
    error: function () {
      swal({
        title: "Error",
        text: "No se pudo obtener la información del Empleado",
        icon: "error",
        button: "OK",
      });
    },
  });
}

// eliminar el usuario
function eliminarEmpleado($id) {
  swal({
    title: "¿Estás seguro?",
    text: "El Empleado será eliminado.",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      let base_url = window.BASE_URL;
      $.ajax({
        url: base_url + "empleado/deleteEmpleado/" + $id,
        type: "POST",
        dataType: "json",
        success: function (response) {
          if (response.ok == true) {
            swal({
              title: "Eliminar el Empleado",
              text: response.post,
              icon: "success",
              button: "OK",
            });
            tableEmpleados.ajax.reload(null, false);
          } else {
            swal({
              title: "No fue posible eliminar el Empleado",
              text: response.post,
              icon: "error",
              button: "OK",
            });
          }
        },
        error: function (xhr, status, error) {
          swal(
            "Error",
            "No se pudo eliminar el Empleado. Intente nuevamente.",
            "error"
          );
        },
      });
    }
  });
}

// SERVICIOS
const tableServicio = new DataTable("#tableServicio", {
  columnDefs: [
    { className: "text-center", targets: [2] },
    { className: "text-left", targets: [0, 1] },
  ],
  ajax: {
    url: window.BASE_URL + "getServices",
    dataSrc: "",
  },
  columns: [
    { data: "nombre" },
    {
      data: "precio",
      render: function (data, type, row) {
        // Formatear el precio con el signo de peso y separadores
        return "$" + parseFloat(data).toLocaleString();
      },
    },
    {
      data: "pago_empleado",
      render: function (data, type, row) {
        // Formatear el precio con el signo de peso y separadores
        return "$" + parseFloat(data).toLocaleString();
      },
    },
    { data: "accion" },
  ],
  responsive: true,
  bDestroy: true,
  iDisplayLength: 10,
  order: [[0, "desc"]],
});

function ModalServicio() {
  document.querySelector("#frmServicio").reset();
  document.querySelector("#idServicio").value = "";
  document.querySelector("#titleModal").innerHTML = "Nuevo Servicio";
  document.querySelector("#btnGuardarServicio").innerHTML = "Guardar";
  $("#modelServicio").modal("show");
}
// insertar y actualizar servicio
document.addEventListener("DOMContentLoaded", function () {
  $("#frmServicio").on("submit", function (event) {
    event.preventDefault(); // Prevenir el envío del formulario por defecto

    let base_url = window.BASE_URL;
    let formData = new FormData(this);
    let idServicio = $("#idServicio").val();
    let url =
      base_url +
      (idServicio ? "services/updateServices" : "services/setServices");

    $.ajax({
      type: "POST",
      url: url,
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        if (response.ok) {
          swal({
            title: "Success",
            text: response.post,
            icon: "success",
            button: "OK",
          });
          // Recargar la tabla de services o redirigir al dashboard
          tableServicio.ajax.reload(null, false);
          document.querySelector("#frmServicio").reset();
          $("#modelServicio").modal("hide");
        } else {
          swal({
            title: "Error",
            text: response.post,
            icon: "error",
            button: "OK",
          });
        }
      },
    });
  });
});

//editar servicio
function editarServicio(id) {
  document
    .querySelector(".modal-header")
    .classList.replace("headerRegister", "headerUpdate");
  document
    .querySelector("#btnGuardarServicio")
    .classList.replace("btn-primary", "btn-info");
  document.querySelector("#btnGuardarServicio").innerHTML = "Actualizar";
  document.querySelector("#titleModal").innerHTML = "Actualizar Servicio";
  document.querySelector("#frmServicio").reset();

  let base_url = window.BASE_URL;
  $.ajax({
    url: base_url + "services/getService/" + id,
    type: "GET",
    dataType: "json",
    success: function (resp) {
      $("#idServicio").val(resp.id);
      $("#nombre").val(resp.nombre);
      $("#precio").val(resp.precio);
      $("#modelServicio").modal("show");
    },
    error: function () {
      swal({
        title: "Error",
        text: "No se pudo obtener la información del Servicio",
        icon: "error",
        button: "OK",
      });
    },
  });
}

// eliminar el servicio
function eliminarServicio($id) {
  swal({
    title: "¿Estás seguro?",
    text: "El Servicio será eliminado y no podrá acceder al sistema.",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      let base_url = window.BASE_URL;
      $.ajax({
        url: base_url + "services/deleteServices/" + $id,
        type: "POST",
        dataType: "json",
        success: function (response) {
          if (response.ok == true) {
            swal({
              title: "Eliminar el Servicio",
              text: response.post,
              icon: "success",
              button: "OK",
            });
            tableServicio.ajax.reload(null, false);
          } else {
            swal({
              title: "No fue posible eliminar el Servicio",
              text: response.post,
              icon: "error",
              button: "OK",
            });
          }
        },
        error: function (xhr, status, error) {
          swal(
            "Error",
            "No se pudo eliminar el Servicio. Intente nuevamente.",
            "error"
          );
        },
      });
    }
  });
}

// PRODUCTOS
const tableProducto = new DataTable("#tableProducto", {
  columnDefs: [
    { className: "text-center", targets: [2] },
    { className: "text-left", targets: [0, 1] },
  ],
  ajax: {
    url: window.BASE_URL + "getProducts",
    dataSrc: "",
  },
  columns: [
    { data: "nombre" },
    { data: "cantidad" },
    {
      data: "v_compra",
      render: function (data, type, row) {
        // Formatear el precio con el signo de peso y separadores
        return "$" + parseFloat(data).toLocaleString();
      },
    },
    {
      data: "v_venta",
      render: function (data, type, row) {
        // Formatear el precio con el signo de peso y separadores
        return "$" + parseFloat(data).toLocaleString();
      },
    },
    { data: "accion" },
  ],
  responsive: true,
  bDestroy: true,
  iDisplayLength: 10,
  order: [[0, "desc"]],
});

function ModalProducto() {
  document.querySelector("#frmProducto").reset();
  document.querySelector("#idProducto").value = "";
  document.querySelector("#titleModal").innerHTML = "Nuevo Producto";
  document.querySelector("#btnGuardarProducto").innerHTML = "Guardar";
  $("#modelProducto").modal("show");
}
// insertar y actualizar servicio
document.addEventListener("DOMContentLoaded", function () {
  $("#frmProducto").on("submit", function (event) {
    event.preventDefault(); // Prevenir el envío del formulario por defecto

    let base_url = window.BASE_URL;
    let formData = new FormData(this);
    let idProducto = $("#idProducto").val();
    let url =
      base_url +
      (idProducto ? "products/updateProducts" : "products/setProducts");

    $.ajax({
      type: "POST",
      url: url,
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        if (response.ok) {
          swal({
            title: "Success",
            text: response.post,
            icon: "success",
            button: "OK",
          });
          // Recargar la tabla de services o redirigir al dashboard
          tableProducto.ajax.reload(null, false);
          document.querySelector("#frmProducto").reset();
          $("#modelProducto").modal("hide");
        } else {
          swal({
            title: "Error",
            text: response.post,
            icon: "error",
            button: "OK",
          });
        }
      },
    });
  });
});

//editar servicio
function editarProducto(id) {
  document
    .querySelector(".modal-header")
    .classList.replace("headerRegister", "headerUpdate");
  document
    .querySelector("#btnGuardarProducto")
    .classList.replace("btn-primary", "btn-info");
  document.querySelector("#btnGuardarProducto").innerHTML = "Actualizar";
  document.querySelector("#titleModal").innerHTML = "Actualizar Producto";
  document.querySelector("#frmProducto").reset();

  let base_url = window.BASE_URL;
  $.ajax({
    url: base_url + "products/getProduct/" + id,
    type: "GET",
    dataType: "json",
    success: function (resp) {
      $("#idProducto").val(resp.id);
      $("#nombre").val(resp.nombre);
      $("#cantidad").val(resp.cantidad);
      $("#v_compra").val(resp.v_compra);
      $("#v_venta").val(resp.v_venta);
      $("#modelProducto").modal("show");
    },
    error: function () {
      swal({
        title: "Error",
        text: "No se pudo obtener la información del Producto",
        icon: "error",
        button: "OK",
      });
    },
  });
}
// TABLA GANACIAS
new DataTable("#tableGanancias", {
  responsive: true,
  destroy: true,
  lengthMenu: [10, 25, 50, 75, 100],
  order: [
      [0, 'desc'] // Ordena por la columna Fecha de venta (índice 0)
  ]
});
// TABLA REPORTES
new DataTable("#tableReportes", {
  responsive: true,
  destroy: true,
  lengthMenu: [10, 25, 50, 75, 100],
  order: [
      [4, 'desc'] // Ordena por la columna Fecha (índice 4)
  ]
});
// eliminar el servicio
function eliminarProducto($id) {
  swal({
    title: "¿Estás seguro?",
    text: "El Producto será eliminado y no podrá acceder al sistema.",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete) {
      let base_url = window.BASE_URL;
      $.ajax({
        url: base_url + "products/deleteProducts/" + $id,
        type: "POST",
        dataType: "json",
        success: function (response) {
          if (response.ok == true) {
            swal({
              title: "Eliminar el Producto",
              text: response.post,
              icon: "success",
              button: "OK",
            });
            tableProducto.ajax.reload(null, false);
          } else {
            swal({
              title: "No fue posible eliminar el Producto",
              text: response.post,
              icon: "error",
              button: "OK",
            });
          }
        },
        error: function (xhr, status, error) {
          swal(
            "Error",
            "No se pudo eliminar el Producto. Intente nuevamente.",
            "error"
          );
        },
      });
    }
  });
}

// AGENDA
const tableAgenda = new DataTable("#tableAgenda", {
  columnDefs: [
    { className: "text-center", targets: [8, 9] },
    { className: "text-left", targets: [0, 1, 2, 3, 4, 5, 6, 7] },
  ],
  ajax: {
    url: window.BASE_URL + "getTurnos",
    dataSrc: "",
  },
  columns: [
    { data: "rol" },
    { data: "nombre" },
    { data: "apellidos" },
    { data: "cedula" },
    { data: "telefono" },
    { data: "servicio" },
    {
      data: "precio",
      render: function (data, type, row) {
        // Formatear el precio con el signo de peso y separadores
        return "$" + parseFloat(data).toLocaleString();
      },
    },
    { data: "date" },
    { data: "time" },
    { data: "estado" },
    { data: "accion" },
  ],
  responsive: true,
  bDestroy: true,
  iDisplayLength: 10,
  order: [[0, "desc"]],
});

// actualizar turnos vencidosfunction removeExpiredTurnos() {
fetch(window.BASE_URL + "turno/removeExpiredTurnos")
  .then((response) => response.json())
  .then((data) => {
    if (data.success) {
      // alert(data.message);
      // Actualiza la lista de turnos si es necesario
    } else {
      // alert('Error al actualizar el estado de los turnos expirados');
    }
  })
  .catch((error) => {
    // alert('Error al contactar con el servidor');
  });

// Llamar a removeExpiredTurnos cuando sea necesario

// finalizar turnos
function atenderTurno(id) {
  $.ajax({
    url: window.BASE_URL + "turno/atenderTurno/" + id,
    type: "POST",
    dataType: "json",
    success: function (response) {
      if (response.success) {
        swal({
          title: "Éxito",
          text: response.message,
          icon: "success",
          button: "OK",
        }).then(() => {
          tableAgenda.ajax.reload(null, false);
        });
      } else {
        swal({
          title: "Error",
          text: response.message,
          icon: "error",
          button: "OK",
        });
      }
    },
    error: function () {
      swal({
        title: "Error",
        text: "No se pudo actualizar el estado del turno.",
        icon: "error",
        button: "OK",
      });
    },
  });
}
// finalizar turno
function finalizarTurno(id) {
  $.ajax({
    url: window.BASE_URL + "turno/finalizarTurno/" + id,
    type: "POST",
    dataType: "json",
    success: function (response) {
      if (response.success) {
        swal({
          title: "Éxito",
          text: response.message,
          icon: "success",
          button: "OK",
        }).then(() => {
          tableAgenda.ajax.reload(null, false);
        });
      } else {
        swal({
          title: "Error",
          text: response.message,
          icon: "error",
          button: "OK",
        });
      }
    },
    error: function () {
      swal({
        title: "Error",
        text: "No se pudo actualizar el estado del turno.",
        icon: "error",
        button: "OK",
      });
    },
  });
}
// anular tuenos
function inactiletTurno(id) {
  $.ajax({
    url: window.BASE_URL + "turno/anularTurno/" + id,
    type: "POST",
    dataType: "json",
    success: function (response) {
      if (response.success) {
        swal({
          title: "Éxito",
          text: response.message,
          icon: "success",
          button: "OK",
        }).then(() => {
          tableAgenda.ajax.reload(null, false);
        });
      } else {
        swal({
          title: "Error",
          text: response.message,
          icon: "error",
          button: "OK",
        });
      }
    },
    error: function () {
      swal({
        title: "Error",
        text: "No se pudo actualizar el estado del turno.",
        icon: "error",
        button: "OK",
      });
    },
  });
}
// Turnos finalizados

new DataTable("#tableTurnos", {
  columnDefs: [
    { className: "text-center", targets: [8, 9] },
    { className: "text-left", targets: [0, 1, 2, 3, 4, 5, 6, 7] },
  ],
  ajax: {
    url: window.BASE_URL + "getTurnos/finalizados",
    dataSrc: "",
  },
  columns: [
    { data: "usuario" },
    { data: "nombre" },
    { data: "apellidos" },
    { data: "cedula" },
    { data: "telefono" },
    { data: "servicio" },
    {
      data: "precio",
      render: function (data, type, row) {
        // Formatear el precio con el signo de peso y separadores
        return "$" + parseFloat(data).toLocaleString();
      },
    },
    { data: "date" },
    { data: "time" },
    { data: "estado" },
    { data: "accion" },
  ],
  responsive: true,
  bDestroy: true,
  iDisplayLength: 10,
  order: [[7, "desc"]],
});

// generar factura
function generarPdf(id) {
  window.location.href =
    window.BASE_URL + "turno-controller/generatePdf/" + id;
}

// mostrar los productor y servicios en el modal para añadir al cliente
function mostrarModalAgregarServicio(turnoId) {
  $.ajax({
    url: window.BASE_URL + "cargarDatosParaModal",
    method: "GET",
    dataType: "json",
    success: function (response) {
      $("#turno_id").val(turnoId);

      // Limpiar y llenar el select de servicios
      $("#servicio_id")
        .empty()
        .append('<option value="">Seleccione un servicio</option>');
      response.servicios.forEach(function (servicio) {
        $("#servicio_id").append(
          '<option value="' + servicio.id + '">' + servicio.nombre + "</option>"
        );
      });

      // Limpiar y llenar el select de productos
      $("#producto_id")
        .empty()
        .append('<option value="">Seleccione un producto</option>');
      response.productos.forEach(function (producto) {
        $("#producto_id").append(
          '<option value="' + producto.id + '">' + producto.nombre + "</option>"
        );
      });

      // Mostrar el modal
      $("#modalAgregarServicioProducto").modal("show");
    },
  });
}
// guardar estos productos y dervicios añadidos
function guardarServicioOProducto() {
  // Obtiene los valores del formulario
  const turnoId = $("#turno_id").val();
  const servicioId = $("#servicio_id").val();
  const productoId = $("#producto_id").val();
  const cantidadProducto = $("#cantidad_producto").val();

  // Crea el objeto de datos a enviar
  const data = {
    turno_id: turnoId,
    servicio_id: servicioId,
    producto_id: productoId,
    cantidad_producto: cantidadProducto,
  };

  // Realiza la petición AJAX
  $.ajax({
    url: window.BASE_URL + "agregarServicioProducto", // Cambia esta URL por la ruta de tu controlador
    method: "POST",
    data: data,
    dataType: "json",
    success: function (response) {
      if (response.success) {
        // Maneja el caso de éxito, muestra un mensaje o actualiza la vista
        swal({
          title: "Success",
          text: response.message,
          icon: "success",
          button: "OK",
        });
        tableAgenda.ajax.reload(null, false);

        $("#modalAgregarServicioProducto").modal("hide"); // Cierra el modal
        // Opcional: Recargar o actualizar la vista
      } else {
        // Maneja el caso de error
        swal({
          title: "Success",
          text: response.message,
          icon: "success",
          button: "OK",
        });
        
      }
    },
    error: function () {
      // Maneja el error en la petición AJAX
      alert("Hubo un error al guardar los datos. Inténtalo nuevamente.");
    },
  });
}

// Manejar el envío del formulario del modal
$("#formAgregarServicioProducto").submit(function (event) {
  event.preventDefault();

  let formData = $(this).serialize();

  $.ajax({
    url: window.BASE_URL + "agregarServicioProducto",
    method: "POST",
    data: formData,
    success: function (response) {
      if (response.success) {
        swal({
          title: "Success",
          text: response.message,
          icon: "success",
          button: "OK",
        });
        $("#modalAgregarServicioProducto").modal("hide");
        // Actualizar la lista de turnos si es necesario
      } else {
        swal({
          title: "Error",
          text: response.message,
          icon: "error",
          button: "OK",
        });
      }
    },
    error: function () {
      alert("Ocurrió un error al agregar el servicio o producto.");
      swal({
        title: "Error",
        text: "Ocurrió un error al agregar el servicio o producto.",
        icon: "error",
        button: "OK",
      });
    },
  });
});

// Mostrar modal para asignar trabajador
function mostrarModalAsignarTrabajador(turnoId) {
  $.ajax({
      url: window.BASE_URL + "turno/getTrabajadores", // Cambia esta URL si es necesario
      method: "GET",
      dataType: "json",
      success: function(response) {
          $("#turno_idi").val(turnoId);
          // Limpiar y llenar el select de trabajadores
          $("#trabajador_id")
              .empty()
              .append('<option value="">Seleccione un trabajador</option>');
          response.trabajadores.forEach(function(trabajador) {
              $("#trabajador_id").append(
                  '<option value="' + trabajador.id + '">' + trabajador.nombre + "</option>"
              );
          });

          // Mostrar el modal
          $("#modalAsignarTrabajador").modal("show");
      },
      error: function(xhr, status, error) {
          console.error("Error fetching trabajadores:", error);
          
      }
  });
}

// Enviar solicitud de asignación
const formAsignar = document.getElementById('formAsignarTrabajador');
if (formAsignar) {
  formAsignar.addEventListener('submit', function(e){
    // tu lógica de asignarTrabajador
    e.preventDefault();

    const formData = new FormData(this);

    // // Verifica los datos antes de enviar
    // for (let [key, value] of formData.entries()) {
    //     console.log(`${key}: ${value}`);
    // }

    fetch(window.BASE_URL + "turno/asignarTrabajador", {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            swal({
              title: "Success",
              text: data.message,
              icon: "success",
              button: "OK",
            });
            tableAgenda.ajax.reload(null, false);

            $('#modalAsignarTrabajador').modal('hide');
            // Actualizar la lista de turnos si es necesario
        } else {
            swal({
              title: "Error",
              text: data.message,
              icon: "error",
              button: "OK",
            });
        }
    })
    .catch(error => {
        console.error('Error assigning trabajador:', error);
    });
  });
}
//   document.getElementById('formAsignarTrabajador').addEventListener('submit', function(e) {
   
// });

// FINANZAS - Inicializar tabla de Ingresos
if (document.querySelector("#tableIngresos")) {
    console.log("function.js: iniciando Ingresos");
    const tableIngresos = new DataTable("#tableIngresos", {
        columnDefs: [
            { className: "text-center", targets: [2, 3] },
            { className: "text-left", targets: [0, 1, 4] }
        ],
        ajax: {
            url: window.BASE_URL + "finanzas/getIngresos",
            dataSrc: "",
            data: function(d) {
                d.month = document.getElementById("monthFilter").value;
                d.year = document.getElementById("yearFilter").value;
            }
        },
        columns: [
            { data: null, render: function(row) { return row.nombre_usuario + ' ' + row.apellidos; } },
            { data: "producto_nombre" },
            { data: "cantidad" },
            {
                data: "valor_total",
                render: function(data) {
                    return "$" + parseFloat(data).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
                }
            },
            { data: "fecha_venta" }
        ],
        responsive: true,
        bDestroy: true,
        iDisplayLength: 10,
        lengthMenu: [10, 25, 50, 75, 100],
        order: [[4, 'desc']],
        initComplete: function(settings, json) {
            console.log("DEBUG getIngresos JSON:", json);
        }
    });
    tableIngresos.on('error.dt', function(e, settings, techNote, message) {
        console.error("ERROR DT Ingresos:", message);
    });
    tableIngresos.on('xhr', function(e, settings, json) {
        let total = json.reduce(function(a, b) {
            return a + parseFloat(b.valor_total);
        }, 0);
        document.getElementById("totalIngresos").innerText =
            "$" + total.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
    });
    // Filtros y gráfico de Ingresos
    function loadChart(year) {
        let url = window.BASE_URL + "/salon_belleza/finanzas/getIngresosMensual";
        if (year) url += "?year=" + year;
        fetch(url)
            .then(res => res.json())
            .then(json => {
                const labels = json.map(i => i.mes);
                const prod = json.map(i => i.producto);
                const serv = json.map(i => i.servicio);
                const ctx = document.getElementById("chartIngresosMonthly").getContext("2d");
                if (window.chartIngresos) window.chartIngresos.destroy();
                window.chartIngresos = new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: labels,
                        datasets: [
                            { label: "Productos", data: prod, backgroundColor: "rgba(54,162,235,0.5)" },
                            { label: "Servicios", data: serv, backgroundColor: "rgba(255,99,132,0.5)" }
                        ]
                    },
                    options: { responsive: true, scales: { y: { beginAtZero: true } } }
                });
            });
    }
    document.getElementById("monthFilter").addEventListener("change", function() {
        tableIngresos.ajax.reload();
        loadChart(document.getElementById("yearFilter").value);
    });
    document.getElementById("yearFilter").addEventListener("change", function() {
        tableIngresos.ajax.reload();
        loadChart(this.value);
    });
    // Carga inicial del gráfico con año actual
    loadChart(document.getElementById("yearFilter").value);
 }

// Función para graficar egresos mensuales
function loadChartEgresos(year, month) {
    let url = window.BASE_URL + "/salon_belleza/finanzas/getEgresosMensual";
    const params = [];
    if (year) params.push("year=" + year);
    if (month) params.push("month=" + month);
    if (params.length) url += "?" + params.join("&");
    fetch(url)
        .then(res => res.json())
        .then(data => {
            const labels = data.map(row => row.mes);
            const valores = data.map(row => parseFloat(row.total));
            const ctx = document.getElementById("chartEgresosMonthly").getContext("2d");
            if (window.chartEgresos) window.chartEgresos.destroy();
            window.chartEgresos = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Egresos mensuales',
                        data: valores,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });
}

// FINANZAS - Inicializar tabla de Egresos
if (document.querySelector("#tableEgresos")) {
    console.log("function.js: iniciando Egresos");
    const tableEgresos = new DataTable("#tableEgresos", {
        columnDefs: [
            { className: "text-center", targets: [2, 3] },
            { className: "text-left", targets: [0, 1] }
        ],
        ajax: {
            url: window.BASE_URL + "/salon_belleza/finanzas/getEgresos",
            dataSrc: "",
            data: function(d) {
                d.month = document.getElementById("monthFilterEgresos").value;
                d.year = document.getElementById("yearFilterEgresos").value;
            }
        },
        columns: [
            { data: "concepto" },
            { data: "tipo" },
            { data: "monto", render: function(data) { return "$" + parseFloat(data).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}); } },
            { data: "fecha" }
        ],
        responsive: true,
        bDestroy: true,
        iDisplayLength: 10,
        lengthMenu: [10, 25, 50, 75, 100],
        order: [[3, 'desc']]
    });
    tableEgresos.on('error.dt', function(e,settings,techNote,message){ console.error(message);} );
    tableEgresos.on('xhr', function(e,settings,json){ /* suma total */ });
    // Función para obtener y mostrar saldo disponible
    function loadSaldoEgresos(year, month) {
        let url = window.BASE_URL + "/salon_belleza/finanzas/getSaldo";
        const params = [];
        if (year) params.push("year=" + year);
        if (month) params.push("month=" + month);
        if (params.length) url += "?" + params.join("&");
        fetch(url)
            .then(res => res.json())
            .then(json => {
                document.getElementById("saldoDisponible").innerText =
                    "$" + parseFloat(json.saldo).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
            });
    }
    // --- INICIO BLOQUE DOMContentLoaded ---
    document.addEventListener("DOMContentLoaded", function() {
        // Listener para mes
        document.getElementById("monthFilterEgresos").addEventListener("change", function() {
            const mon = this.value;
            const yr  = document.getElementById("yearFilterEgresos").value;
            tableEgresos.ajax.reload();
            loadChartEgresos(yr, mon);
            loadSaldoEgresos(yr, mon);
        });
        // Listener para año
        document.getElementById("yearFilterEgresos").addEventListener("change", function() {
            const yr  = this.value;
            const mon = document.getElementById("monthFilterEgresos").value;
            tableEgresos.ajax.reload();
            loadChartEgresos(yr, mon);
            loadSaldoEgresos(yr, mon);
        });
        // Carga inicial
        const initYr  = document.getElementById("yearFilterEgresos").value;
        const initMon = document.getElementById("monthFilterEgresos").value;
        loadChartEgresos(initYr, initMon);
        loadSaldoEgresos(initYr, initMon);
        // Botón Retirar
        document.getElementById("btnRetirarEgreso").addEventListener("click", function() {
            document.getElementById("monthEgresoHidden").value = document.getElementById("monthFilterEgresos").value;
            document.getElementById("yearEgresoHidden").value  = document.getElementById("yearFilterEgresos").value;
            $('#modalRetirarEgreso').modal('show');
        });
        // Formulario Retiro
        document.getElementById("formRetirarEgreso").addEventListener("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch(window.BASE_URL + "finanzas/retirar", { method:"POST", body: formData })
          .then(res => res.json())
          .then(json => {
              const mon = document.getElementById("monthFilterEgresos").value;
              const yr  = document.getElementById("yearFilterEgresos").value;
              if (json.success) {
                  let saldoMsg = '';
                  if (json.saldo_anterior !== undefined && json.saldo_nuevo !== undefined) {
                      saldoMsg = `\nSaldo anterior: $${parseFloat(json.saldo_anterior).toFixed(2)}\nSaldo nuevo: $${parseFloat(json.saldo_nuevo).toFixed(2)}`;
                  }
                  swal({ title:"Éxito", text:json.message + saldoMsg, icon:"success", button:"OK" })
                      .then(() => {
                          $('#modalRetirarEgreso').modal('hide');
                          tableEgresos.ajax.reload();
                          loadChartEgresos(yr, mon);
                          loadSaldoEgresos(yr, mon);
                      });
              } else {
                  swal({ title:"Error", text:json.message, icon:"error", button:"OK" });
              }
          }).catch(err => console.error("Error al retirar:", err));
    });
    }); // <-- cierre de DOMContentLoaded
}
