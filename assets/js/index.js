$(document).ready(function () {
  $("#consultar").click(buscarCedula);
  $("#consCedula").on("keyup", function (e) {
    if (e.key === "Enter" || e.keyCode === 13) {
      buscarCedula();
    }
  });

  $("#openModal").click(function () {
    $("#consCedula").val("");
    $("#mostrar-consulta").hide();
  });

  $(".numeric").on("input", function (event) {
    this.value = this.value.replace(/[^0-9]/g, "");
  });

  $("#cedula").focusout(buscarParticipante);
});

function buscarParticipante() {
  var cedula = $("#cedula").val();
  if (cedula == "") {
    return;
  }

  $.ajax({
    url: "../controllers/consultarParticipante.php",
    type: "POST",
    data: {
      cedula: cedula,
    },
    dataType: "json",
    success: function (data) {
      console.log(data);
      if (data.success) {
        Swal.fire({
            title: "¿Es usted " + data.participante.fullname + "?",
            text: '¿Desea autocompletar su información?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $("#fullname").val(data.participante.fullname);
                $("#celular").val(data.participante.telefono);
                $("#correo").val(data.participante.email);
                $("#agencia").val(data.participante.agencia);
                $("#reserva").focus();
            } 
        })      
      }
    },
    error: function (data) {
      console.log(data);
      Swal.fire("Error!", "Error de conexión con el servidor", "error");
    },
  });
}

function buscarCedula() {
  var cedula = $("#consCedula").val();

  if (cedula == "") {
    Swal.fire("Error!", "Cedula no ingresada", "error");
    return;
  }
  $.ajax({
    url: "../controllers/consultarCedula.php",
    type: "POST",
    data: {
      cedula: cedula,
    },
    success: function (data) {
      $("#mostrar-consulta").html(data);
      $("#mostrar-consulta").show();
    },
    error: function (data) {
      console.log(data);
      Swal.fire("Error!", "Error de conexión con el servidor", "error");
    },
  });
}

function sendForm() {
  var cedula = $("#cedula").val();
  var celular = $("#celular").val();
  var correo = $("#correo").val();
  var nombre = $("#fullname").val();
  var agencia = $("#agencia").val();
  var reserva = $("#reserva").val();
  var hotel = $("#hotel").val();
  var destacado = $("#hotel option:selected").data("destacado");
  var asesor = $("#asesor").val();

  var data = {
    cedula: cedula,
    telefono: celular,
    email: correo,
    fullname: nombre,
    agencia: agencia,
    reserva: reserva,
    hotel: hotel,
    destacado: destacado,
    asesor: asesor,
  };

  var formData = new FormData();
  formData.append("data", JSON.stringify(data));
  formData.append("file", $("#liquidacion")[0].files[0]);

  $.ajax({
    url: "../controllers/generarParticipacion.php",
    type: "POST",
    data: formData,
    contentType: false,
    cache: false,
    processData: false,
    dataType: "json",
    success: function (data) {
      console.log(data);
      if (data.success) {
        Swal.fire("Exito!", data.message, "success").then(function () {
          window.location.reload();
        });
      } else {
        Swal.fire("Error!", data.error, "error");
      }
    },
    error: function (data) {
      console.log(data);
      Swal.fire("Error!", "Error de conexión con el servidor", "error");
    },
  });
}

(function () {
  "use strict";
  window.addEventListener(
    "load",
    function () {
      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      var forms = document.getElementsByClassName("needs-validation");
      // Loop over them and prevent submission
      var validation = Array.prototype.filter.call(forms, function (form) {
        form.addEventListener(
          "submit",
          function (event) {
            if (form.checkValidity() != false) {
              sendForm();
            }
            event.preventDefault();
            event.stopPropagation();
            form.classList.add("was-validated");
          },
          false
        );
      });
    },
    false
  );
})();
