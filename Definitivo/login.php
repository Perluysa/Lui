<!doctype html>
<html lang="es">
<head>
    <title>Iniciar Sesión</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<div class="container">

    <div class="row">

        <div class="col-md-3"></div>

        <div class="col-md-6">

            <br/> <br/> <br/> <br/> <br/>

            <div class="card">
                <div class="card-header bg-primary text-light">
                    Inicio de Sesión
                </div>

                <div class="card-body bg-dark">

                    <form action="./sesion/validacion.php" method="POST">

                        <div class="form-group">
                            <label>Nombre de Usuario</label>
                            <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Ingresa tu Usuario">
                        </div>

                        <div class="form-group" style=" margin-top: 10px;">
                            <label>Contraseña</label>
                            <input type="password" class="form-control" name="contraseña" id="contraseña" placeholder="Escribe tu Contraseña">
                        </div>

                        <button type="submit" class="btn btn-primary" style=" margin-top: 10px;">Iniciar Sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
