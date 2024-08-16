// USUARIOS

const tableUsuarios = new DataTable("#tableUsuarios", {
  columnDefs: [
    { className: "text-center", targets: [7] },
    { className: "text-left", targets: [0, 1, 2, 3, 4, 5, 6] },
  ],
  ajax: {
    url: "http://localhost/salon_belleza/" + "getUsers",
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

    let base_url = "http://localhost/salon_belleza/";
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

  let base_url = "http://localhost/salon_belleza/";
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
      let base_url = "http://localhost/salon_belleza/";
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

  let base_url = "http://localhost/salon_belleza/";
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
  let base_url = "http://localhost/salon_belleza/";

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
  var formData = new FormData(document.getElementById("frmPermisos"));
  let base_url = "http://localhost/salon_belleza/";

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
    url: "http://localhost/salon_belleza/" + "getClients",
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

    let base_url = "http://localhost/salon_belleza/";
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

  let base_url = "http://localhost/salon_belleza/";
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
      let base_url = "http://localhost/salon_belleza/";
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
    url: "http://localhost/salon_belleza/" + "getEmpleados",
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

    let base_url = "http://localhost/salon_belleza/";
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

  let base_url = "http://localhost/salon_belleza/";
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
      let base_url = "http://localhost/salon_belleza/";
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
    url: "http://localhost/salon_belleza/" + "getServices",
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

    let base_url = "http://localhost/salon_belleza/";
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

  let base_url = "http://localhost/salon_belleza/";
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
      let base_url = "http://localhost/salon_belleza/";
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
    url: "http://localhost/salon_belleza/" + "getProducts",
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

    let base_url = "http://localhost/salon_belleza/";
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

  let base_url = "http://localhost/salon_belleza/";
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
      let base_url = "http://localhost/salon_belleza/";
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
    url: "http://localhost/salon_belleza/" + "getTurnos",
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
fetch("http://localhost/salon_belleza/turno/removeExpiredTurnos")
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
    url: `http://localhost/salon_belleza/turno/atenderTurno/${id}`,
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
    url: `http://localhost/salon_belleza/turno/finalizarTurno/${id}`,
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
function inactivarTurno(id) {
  $.ajax({
    url: `http://localhost/salon_belleza/turno/anularTurno/${id}`,
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
    url: "http://localhost/salon_belleza/" + "getTurnos/finalizados",
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
    "http://localhost/salon_belleza/turno-controller/generatePdf/" + id;
}

// mostrar los productor y servicios en el modal para añadir al cliente
function mostrarModalAgregarServicio(turnoId) {
  $.ajax({
    url: "http://localhost/salon_belleza/cargarDatosParaModal",
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
    url: "http://localhost/salon_belleza/agregarServicioProducto", // Cambia esta URL por la ruta de tu controlador
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
    url: "http://localhost/salon_belleza/agregarServicioProducto",
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
      url: "http://localhost/salon_belleza/turno/getTrabajadores", // Cambia esta URL si es necesario
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

  document.getElementById('formAsignarTrabajador').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    // // Verifica los datos antes de enviar
    // for (let [key, value] of formData.entries()) {
    //     console.log(`${key}: ${value}`);
    // }

    fetch('http://localhost/salon_belleza/turno/asignarTrabajador', {
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
