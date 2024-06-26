<?php
include("conexion.php");

// Agregar nuevo paciente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];
    $odontologo_nombre = $_POST['odontologo']; // Nuevo campo agregado

    $sql = "INSERT INTO pacientes (nombre, apellido, telefono, email, odontologo_nombre) VALUES ('$nombre', '$apellido', '$telefono', '$email', $odontologo_nombre)";
    if ($conn->query($sql) === TRUE) {
        echo "Paciente agregado exitosamente";
    } else {
        echo "Error al agregar paciente: " . $conn->error;
    }
}

// Eliminar paciente
if (isset($_GET["eliminar"])) {
    $idEliminar = $_GET["eliminar"];
    $sqlEliminar = "DELETE FROM pacientes WHERE id=$idEliminar";

    if ($conn->query($sqlEliminar) === TRUE) {
        echo "Paciente eliminado exitosamente";
    } else {
        echo "Error al eliminar paciente: " . $conn->error;
    }
}

// Obtener lista de pacientes
$sqlPacientes = "SELECT * FROM pacientes";

// Filtrar pacientes si se ha enviado un término de búsqueda
if (isset($_GET["buscar"]) && !empty($_GET["buscar"])) {
    $buscar = $_GET["buscar"];
    $sqlPacientes .= " WHERE nombre LIKE '%$buscar%' OR apellido LIKE '%$buscar%' OR telefono LIKE '%$buscar%' OR email LIKE '%$buscar%'";
}

$resultPacientes = $conn->query($sqlPacientes);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Pacientes</title>
     <link rel="stylesheet" type="text/css" href="css/estilos.css">
</head>
<body>
     <div class="container">

    <h2>Gestionar Pacientes</h2>

        <form method="POST" action="" class="add-form">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" required>

            <label for="apellido">Apellido:</label>
            <input type="text" name="apellido" required>

            <label for="telefono">Teléfono:</label>
            <input type="text" name="telefono">

            <label for="email">Email:</label>
            <input type="email" name="email">

            <label for="odontologo">Odontólogo:</label>
<select name="odontologo" id="odontologo" required>
    <option value="">Seleccionar Odontólogo</option>

    <?php
    // Conexión a la base de datos
    // Reemplaza los valores según tu configuración
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "dental_app";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $database);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Error en la conexión: " . $conn->connect_error);
    }

    // Consulta SQL para obtener la lista de odontólogos
    $sql = "SELECT id, nombre FROM odontologos";
    
    $result = $conn->query($sql);

    // Mostrar opciones en el select
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<option value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
        }
    }

    // Cerrar conexión
    $conn->close();
    ?>
</select>


            <button type="submit">Agregar Paciente</button>
        </form>

    <!-- Campo de búsqueda -->
    <form method="GET" action="">
        <label for="buscar">Buscar:</label>
        <input type="text" name="buscar" placeholder="Ingrese término de búsqueda">
        <button type="submit">Buscar</button>
    </form>

    <table border="1">
        <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Odontologo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
        <?php
        if ($resultPacientes->num_rows > 0) {
            while ($row = $resultPacientes->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["id"] . "</td>
                        <td>" . $row["nombre"] . "</td>
                        <td>" . $row["apellido"] . "</td>
                        <td>" . $row["telefono"] . "</td>
                        <td>" . $row["email"] . "</td>
                        <td>" . $row["odontologo_nombre"] . "</td>
                        <td>
                            <a href='pacientes.php?eliminar=" . $row["id"] . "'>Eliminar</a>
                            <a href='editar_paciente.php?id=" . $row["id"] . "'>Modificar</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No hay pacientes registrados</td></tr>";
        }
        ?>
    		</tbody>
    	</table>
    	
    	<button type="inicio"><a href="index.php">INICIO</a></button>
   </div>
</body>
</html>
