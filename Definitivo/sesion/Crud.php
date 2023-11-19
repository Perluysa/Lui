<?php
// VALIDACION DE INICIAR SESION
session_start();
$varsesion = $_SESSION['usuario'];
if($varsesion == null || $varsesion = ""){
    echo "INICIE SESION";
    die();
}






// CRUD
$txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : "";
$txtNombre = (isset($_POST['txtNombre'])) ? $_POST['txtNombre'] : "";
$txtIdentificación = (isset($_POST['txtIdentificación'])) ? $_POST['txtIdentificación'] : "";
$txtCorreo = (isset($_POST['txtCorreo'])) ? $_POST['txtCorreo'] : "";
$txtImagen = (isset($_FILES['txtImagen']['name'])) ? $_FILES['txtImagen']['name'] : "";
$txtRol = (isset($_POST['txtRol'])) ? $_POST['txtRol'] : "";
$accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";

include("../Config/bd.php");

switch ($accion) {
    case "Agregar":
        $sentenciaSQL = $conexion->prepare("INSERT INTO `usuarios` (`ID`, `Nombre`, `Identificación`, `Correo`, `Imagen`, `Rol`) VALUES (NULL, :nombre, :identificacion, :correo, :imagen, :rol)");
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->bindParam(':identificacion', $txtIdentificación);
        $sentenciaSQL->bindParam(':correo', $txtCorreo);

        $fecha = new DateTime();
        $NombreArchivo = ($txtImagen != "") ? $fecha->getTimestamp() . "_" . $_FILES["txtImagen"]["name"] : "imagen.jpg";
        $tmpImagen = $_FILES["txtImagen"]["tmp_name"];

        if ($tmpImagen != "") {
            move_uploaded_file($tmpImagen, "../img/" . $NombreArchivo);
        }

        $sentenciaSQL->bindParam(':imagen', $NombreArchivo);
        $sentenciaSQL->bindParam(':rol', $txtRol);

        if ($sentenciaSQL->execute()) {
            // Puedes agregar lógica adicional aquí si es necesario
        } else {
            // Puedes manejar el error de alguna manera si es necesario
        }
        break;

        case "Modificar":
            $sentenciaSQL = $conexion->prepare("UPDATE `usuarios` SET Nombre = :nombre WHERE ID = :ID");
            $sentenciaSQL->bindParam(':nombre', $txtNombre);
            $sentenciaSQL->bindParam(':ID', $txtID);
            $sentenciaSQL->execute();
        
            if ($txtImagen != "") {
                $fecha = new DateTime();
                $NombreArchivo = $fecha->getTimestamp() . "_" . $_FILES["txtImagen"]["name"];
                $tmpImagen = $_FILES["txtImagen"]["tmp_name"];
        
                move_uploaded_file($tmpImagen, "../img/" . $NombreArchivo);
        
                $sentenciaSQL = $conexion->prepare("UPDATE `usuarios` SET Imagen = :imagen WHERE ID = :ID");
                $sentenciaSQL->bindParam(':imagen', $NombreArchivo);
                $sentenciaSQL->bindParam(':ID', $txtID);
                $sentenciaSQL->execute();
            }
            break;
        

    case "Cancelar":
        // Agrega lógica de cancelar aquí
        break;

    case "Editar":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM `usuarios` WHERE ID=:ID");
        $sentenciaSQL->bindParam(':ID', $txtID);
        $sentenciaSQL->execute();
        $usuario = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);

        $txtNombre = $usuario['Nombre'];
        $txtIdentificación = $usuario['Identificación'];
        $txtCorreo = $usuario['Correo'];
        $txtImagen = $usuario['Imagen'];
        $txtRol = $usuario['Rol'];
        break;

    case "Borrar":
        $sentenciaSQL = $conexion->prepare("DELETE FROM `usuarios` WHERE ID=:ID");
        $sentenciaSQL->bindParam(':ID', $txtID);
        $sentenciaSQL->execute();
        break;
}

$sentenciaSQL = $conexion->prepare("SELECT * FROM `usuarios`");
$sentenciaSQL->execute();
$ListaUsuarios = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <title>Document</title>
</head>

<body>
<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
        <div class="nav navbar-nav">

            <a class="nav-item nav-link " href="./cerrar_sesion.php">Cerrar Sesión</a>
        </div>
        
    </nav>

    <div class="container">
        <div class="row">

            <div class="col-md-4 ">
        <div class="card " style=" margin-top: 50px;">

             
            <div class="card-header bg-primary text-light" >
                Datos usuarios
            </div>
            <div class="card-body">

                <form method="POST" enctype="multipart/form-data" >
                    <div class="form-group">
                        <label for="txtID">ID:</label>
                        <input type="text"  readonly class="form-control" value="<?php echo $txtID; ?>"
                            id="txtID" name="txtID" placeholder="ID">
                    </div>

                    <div class="form-group" style=" margin-top: 10px;">
                        <label for="txtNombre">Nombre:</label>
                        <input type="text"  class="form-control" value="<?php echo $txtNombre; ?>"
                            id="txtNombre" name="txtNombre" placeholder="Nombre">
                    </div>

                    <div class="form-group" style=" margin-top: 10px;">
                        <label for="txtIdentificación">Identificación:</label>
                        <input type="text"  class="form-control" value="<?php echo $txtIdentificación; ?>"
                            id="txtIdentificación" name="txtIdentificación" placeholder="Identificación">
                    </div>

                    <div class="form-group" style=" margin-top: 10px;">
                        <label for="txtCorreo">Correo:</label>
                        <input type="email"  class="form-control" value="<?php echo $txtCorreo; ?>"
                            id="txtCorreo" name="txtCorreo" placeholder="Correo">
                    </div>

                    <div class="form-group" style=" margin-top: 10px;">
                        <label for="txtImagen">Imagen</label>
                        <img src="../img/<?php echo $txtImagen; ?>" width="50" alt="Imagen de usuario">
                        <input type="file"  class="form-control" id="txtImagen" name="txtImagen">
                    </div>

                    <div class="form-group" style=" margin-top: 10px;">
                        <label for="txtRol">Rol:</label>
                        <input type="text"  class="form-control" value="<?php echo $txtRol; ?>" id="txtRol"
                            name="txtRol" placeholder="Rol">
                    </div>

                    <div class="btn-group" role="group" aria-label="" style=" margin-top: 10px;">
                        <button type="submit" name="accion" value="Agregar" class="btn btn-success">Agregar</button>
                        <button type="submit" name="accion" value="Modificar" class="btn btn-dark">Modificar
                        </button>
                        <button type="submit" name="accion" value="Cancelar" class="btn btn-primary">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8" style=" margin-top: 50px;">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Identificación</th>
                    <th>Correo</th>
                    <th>Imagen</th>
                    <th>Rol</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ListaUsuarios as $usuario) { ?>
                    <tr>
                        <td><?php echo $usuario['ID']; ?></td>
                        <td><?php echo $usuario['Nombre']; ?></td>
                        <td><?php echo $usuario['Identificación']; ?></td>
                        <td><?php echo $usuario['Correo']; ?></td>
                        <td>
    <img src="../img/<?php echo $usuario['Imagen']; ?>" width="50" alt="Imagen de usuario">
</td>

                        <td><?php echo $usuario['Rol']; ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="txtID" id="txtID" value="<?php echo $usuario['ID']; ?>">

                                <input type="submit" name="accion" value="Borrar" class="btn btn-primary">
                            
                                <input type="submit" name="accion" value="Editar" class="btn btn-secondary">
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
        </div>
    </div>

    
</body>

</html>

<script src="../Config/Formulario_Validacion.js"></script>