<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Iniciar Sesión</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Puedes cambiar la URL de Bootstrap según la versión que desees utilizar -->
  <style>
    body {
      background-color: #f8f9fa;
    }

    .container {
      max-width: 400px;
    }

    .logo {
      max-width: 100%;
      height: auto;
    }

    .form-signin {
      background-color: #ffffff;
      border: 1px solid #dcdcdc;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0px 0px 10px 0px #dcdcdc;
    }

    .btn-primary {
      background-color: #28a745;
      border: 1px solid #218838;
    }

    .btn-primary:hover {
      background-color: #218838;
      border: 1px solid #1e7e34;
    }

    .btn-block {
      margin-top: 20px;
    }

    /* Mejorado diseño de requisitos */
    #passwordRequirements {
      margin-top: 10px;
      padding: 10px;
      border: 1px solid #dcdcdc;
      border-radius: 5px;
    }

    .requirement {
      display: flex;
      align-items: center;
      margin-bottom: 5px;
    }

    .requirement span {
      margin-left: 5px;
    }

    .checkmark {
      color: #28a745 !important; /* Color verde para ✓ */
    }

    .cross {
      color: #dc3545 !important; /* Color rojo para ✗ */
    }
  </style>
</head>
<body>
  <div class="container mt-5">
    <div class="text-center mb-4">
      <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo">
    </div>

    <form action="{{ route('usuarios.updatePasswordFirstLogin', $userId) }}" method="POST" id="passwordForm">
      @csrf
      @method('PUT')

      <div class="form-group">
        <label for="password" class="sr-only">Nueva Contraseña</label>
        <div class="input-group">
          <input type="password" id="password" name="password" class="form-control" placeholder="Nueva Contraseña" required autocomplete="disable">
          <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" id="showPasswordBtn">Mostrar</button>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="password_repetida" class="sr-only">Repetir Contraseña</label>
        <input type="password" id="password_repetida" class="form-control" placeholder="Repetir Contraseña" required autocomplete="disable">
      </div>

      <!-- Muestra en tiempo real los requisitos cumplidos -->
      <div id="passwordRequirements" class="mb-3"></div>

      <button class="btn btn-lg btn-primary btn-block" type="submit">Cambiar Contraseña</button>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Bootstrap JS (después de jQuery) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Script para validar contraseñas -->
  <script>
    $(document).ready(function () {
      // Botón para mostrar/ocultar la contraseña
      $("#showPasswordBtn").on("mousedown", function () {
        $("#password").attr("type", "text");
      }).on("mouseup mouseleave", function () {
        $("#password").attr("type", "password");
      });

      $("#passwordForm").submit(function (event) {
        var password = $("#password").val();
        var password_repetida = $("#password_repetida").val();
        var requirementsMet = checkPasswordRequirements(password);

        // Validar coincidencia de contraseñas
        if (password !== password_repetida) {
          alert("Las contraseñas no coinciden");
          event.preventDefault(); // Evitar el envío del formulario
        }

        // Validar requisitos de contraseña
        if (!requirementsMet.hasUpperCase || !requirementsMet.hasSpecialChar || !requirementsMet.hasNumber || !requirementsMet.hasMinLength) {
          alert("La contraseña no cumple con todos los requisitos");
          event.preventDefault(); // Evitar el envío del formulario
        }
      });

      $("#password").on("input", function () {
        var password = $(this).val();
        var requirementsMet = checkPasswordRequirements(password);
        displayRequirements(requirementsMet);
        checkPasswordMatch();
      });

      $("#password_repetida").on("input", function () {
        checkPasswordMatch();
      });

      function checkPasswordRequirements(password) {
        var hasUpperCase = /[A-Z]/.test(password);
        var hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        var hasNumber = /\d/.test(password);
        var hasMinLength = password.length >= 8;

        return {
          hasUpperCase: hasUpperCase,
          hasSpecialChar: hasSpecialChar,
          hasNumber: hasNumber,
          hasMinLength: hasMinLength,
        };
      }

      function checkPasswordMatch() {
        var password = $("#password").val();
        var password_repetida = $("#password_repetida").val();

        if (password === password_repetida) {
          $("#password_repetida").removeClass("is-invalid").addClass("is-valid");
        } else {
          $("#password_repetida").removeClass("is-valid").addClass("is-invalid");
        }
      }

      function displayRequirements(requirementsMet) {
        var output = "<strong>Requisitos:</strong>";

        output += "<div class='requirement'>";
        output += "<span class='" + (requirementsMet.hasUpperCase ? 'checkmark' : 'cross') + "'>" + (requirementsMet.hasUpperCase ? "✓" : "✗") + "</span>";
        output += "<span>Mayúscula</span></div>";

        output += "<div class='requirement'>";
        output += "<span class='" + (requirementsMet.hasSpecialChar ? 'checkmark' : 'cross') + "'>" + (requirementsMet.hasSpecialChar ? "✓" : "✗") + "</span>";
        output += "<span>Carácter Especial</span></div>";

        output += "<div class='requirement'>";
        output += "<span class='" + (requirementsMet.hasNumber ? 'checkmark' : 'cross') + "'>" + (requirementsMet.hasNumber ? "✓" : "✗") + "</span>";
        output += "<span>Número</span></div>";

        output += "<div class='requirement'>";
        output += "<span class='" + (requirementsMet.hasMinLength ? 'checkmark' : 'cross') + "'>" + (requirementsMet.hasMinLength ? "✓" : "✗") + "</span>";
        output += "<span>Longitud mínima de 8 caracteres</span></div>";

        $("#passwordRequirements").html(output);
      }


    });
  </script>
</body>
</html>
