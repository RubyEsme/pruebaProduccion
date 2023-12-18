<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Registro</title>
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
  </style>
</head>
<body>
  <div class="container mt-5">
    <div class="text-center mb-4">
      <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo">
    </div>

    <form class="form-signin" method="POST" action="{{route('validar-registro')}}">
        @csrf
      <div class="form-group">
        <label for="inputNombre" class="sr-only">Nombre</label>
        <input type="text" id="userInput" name="name"class="form-control" placeholder="Nombre" required autofocus autocomplete="disable">
      </div>

      <div class="form-group">
        <label for="inputEmail" class="sr-only">Email</label>
        <input type="email" id="emailInput" name="email" class="form-control" placeholder="Email" required autocomplete="disable">
      </div>

      <div class="form-group">
        <label for="inputContraseña" class="sr-only">Contraseña</label>
        <input type="password" id="passwordInput" name="password" class="form-control" placeholder="Contraseña" required autocomplete="disable">
      </div>

      <div class="form-group">
          <label for="inputRole" class="sr-only">Rol</label>
          <select id="inputRole" name="role" class="form-control" required>
              <option value="admin">Admin</option>
               <option value="planeacion">Planeacion</option>
              <option value="linea">Línea</option>
              <option value="esmalte">Esmalte</option>
              <option value="almacen">Almacén</option>
          </select>
      </div>

      <button class="btn btn-lg btn-primary btn-block" type="submit">Registrar</button>
      <a href = "{{ route('welcome') }}" class="btn btn-lg btn-secondary btn-block">Volver</a>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
