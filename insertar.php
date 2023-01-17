<?php
$colegio = new PDO("mysql:host=localhost;dbname=colegio", "root", "");

if (isset($_POST['tipoNotaModificar'])) $tipoNotaModificar = $_POST['tipoNotaModificar'];
if (isset($_POST['mesModificar'])) $mesModificar = $_POST['mesModificar'];
if (isset($_POST['alumnoModificar'])) $alumnoModificar = $_POST['alumnoModificar'];

$consultaClase = "SELECT id,nombreClase FROM colegio.clase WHERE clase.nombreClase NOT LIKE 'Todas' ORDER BY nombreClase DESC";
$consultaAlu = "SELECT id,concat(nombre,' ',apellidos) as nombreCompleto FROM colegio.alumno";
$consultaTipoNota = "SELECT id,tipoNota FROM colegio.tipo_nota";
$consultaMes = "SELECT * FROM colegio.mes ORDER BY mes.id";


$mensajeA = "";
$mensajeN = "";
$mensajeM = "";





if (isset($_POST['ver'])) header('Location: clase.php');

if (isset($_POST['añadirAlu'])) {
    $nombreAlu = $_POST['nombre'];
    $apellidosAlu = $_POST['apellidos'];
    $claseAlu = $_POST['clase'];

    if (!empty($nombreAlu) && !empty($apellidosAlu)) {
        $insertarAlu = $colegio->query("INSERT INTO alumno (nombre, apellidos, nombreClase) VALUES ('$nombreAlu', '$apellidosAlu', (SELECT nombreClase FROM clase WHERE id='$claseAlu'))");
        if ($insertarAlu) {
            $mensajeA = "Se han introducidos los datos correctamente";
        } else {
            $mensajeA = "No se han introducidos los datos";
        }
    } else {
        $mensajeA = "Rellene los campos";
    }
}

if (isset($_POST['añadirNota'])) {
    $nombreNota = $_POST['alumno'];
    $numNota = $_POST['nota'];
    $tipoNota = $_POST['tipoNota'];
    $mesNota = $_POST['mesNota'];

    if (!empty($numNota) || is_numeric($numNota)) {
        $insertarNota = $colegio->query("INSERT INTO notas (nota, tipo_nota, id_alumno, mes) VALUES ('$numNota', (SELECT tipoNota FROM tipo_nota WHERE id='$tipoNota'), '$nombreNota', (SELECT mes FROM mes WHERE id='$mesNota'))");
        if ($insertarNota) {
            $mensajeN = "Se han introducidos los datos correctamente";
        } else {
            $mensajeN = "No se han introducidos los datos";
        }
    } else {
        $mensajeN = "Rellene los campos correctamente";
    }
}

if (isset($_POST['modificar'])) {
    $modificarIdNota = $_POST['modificar'];
    $modificaNota = $_POST['notaModifica'];
    $modificaMes = $_POST['mesModifica'];
    $moficaTipoNota = $_POST['tipoNotaModifica'];
    $modificarNota = $colegio->query("UPDATE notas SET nota = '$modificaNota', tipo_nota = (SELECT tipoNota FROM tipo_nota WHERE id='$moficaTipoNota'), mes = (SELECT mes FROM mes WHERE id='$modificaMes') WHERE notas.id_nota = '$modificarIdNota' ");
    if (!empty($modificaNota) || is_numeric($modificaNota)) {
        if ($modificarNota) {
            $mensajeM = "Se ha modificado la nota";
        } else {
            $mensajeM = "No se ha podido aplicar los cambios";
        }
    } else $mensajeM = "Rellene los campos correctamente";
}

//Función para la muestra de datos en los selects
function mostrar($base,$consulta, $parametro)
{
    $resul = $base->query($consulta);
    while ($row = $resul->fetch()) {
        echo "<option value='$row[0]'";
        if (isset($parametro) && $parametro == $row[0]) {
            echo " selected='true'";
        }
        echo ">" . $row[1] . "</option>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Permanent+Marker&display=swap');
    </style>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>

    <link rel="stylesheet" href="css/estilo.css" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
</head>

<body>
    <div class="bg-image" style="background-image: url('img/fondoMostrar.jpg'); background-attachment: fixed; background-size: cover; height:auto; padding-bottom:35vh;">
        <br>
        <table>
            <tr>
                <th>
                    <h3>Añadir alumno nuevo</h3>
                </th>
            </tr>
            <tr>
                <td>
                    <form class="form-inline" action="" method="post">
                        <div class="form-group">
                            <label class="sr-only" for="">Nombre</label>
                            <input type="text" class="form-control" name="nombre" placeholder="Nombre" id="">
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="">Apellidos</label>
                            <input type="text" class="form-control" placeholder="Apellidos" name="apellidos" id="">
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="">Clase</label>
                            <select name="clase" class="selectpicker">
                                <?php
                                mostrar($colegio,$consultaClase, $clase);
                                ?>
                            </select>
                        </div>

                        <button type="submit" class='btn btn-info' name="añadirAlu">Añadir Alumno</button>
                    </form>
                </td>
            </tr>
        </table><br>
        <p style="color: white;"><?= $mensajeA ?></p>
        <br>
        <h3>Añadir nota alumno</h3>
        <form class="form-inline" action="" method="post">
            <table>
                <tr>
                    <div class="form-group">
                        <td><label for="">Nombre:</label>
                            <select name='alumno' class='selectpicker' data-show-subtext='true' data-live-search='true'>
                                <?php
                                mostrar($colegio,$consultaAlu, $alumno);
                                ?>
                            </select>
                        </td>
                    </div>
                    <div class="form-group">
                        <td><label for="">Nota:</label>
                            <input class="form-control" type="text" name="nota" id="">
                        </td>
                    </div>
                    <div class="form-group">
                        <td><label for="">Tipo:</label>
                            <select name='tipoNota' class='selectpicker'>
                                <?php
                                mostrar($colegio,$consultaTipoNota, $tipoNota);
                                ?>
                            </select>
                        </td>
                    </div>
                    <div class="form-group">
                        <td><label for="">Mes:</label>
                            <select name='mesNota' class='selectpicker'>
                                <?php
                                mostrar($colegio,$consultaMes, $mes);
                                ?>
                            </select>
                        </td>
                    </div>
                    <td><button type="submit" class="btn btn-danger" name="añadirNota">Añadir Nota</button></td>

                </tr>
            </table>
        </form><br>
        <p style="color: white;"><?= $mensajeN ?></p>
        <br>
        <h3>Modificar nota</h3>
        <form action="" method="post">
            <table class="table">
                <tr>
                    <td>
                        <label for="">Alumno: </label>
                        <select name='alumnoModificar' class='selectpicker' data-show-subtext='true' data-live-search='true'>
                            <?php
                            mostrar($colegio,$consultaAlu, $alumnoModificar);
                            ?>
                        </select>
                    </td>
                    <td>
                        <label for="">Tipo: </label>
                        <select name='tipoNotaModificar' class='selectpicker'>
                            <?php
                            mostrar($colegio,$consultaTipoNota,$tipoNotaModificar);
                            ?>
                        </select>
                    </td>
                    <td>
                        <label for="">Mes: </label>

                        <select name='mesModificar' class='selectpicker'>
                            <?php
                            mostrar($colegio,$consultaMes, $mesModificar);
                            ?>
                        </select>
                    </td>
                    <td>
                        <button type="submit" class='btn btn-primary' name="buscarNota">Buscar Notas</button>
                    </td>
                </tr>

                <?php
                if (isset($_POST['buscarNota'])) {
                    $buscaId = $_POST['alumnoModificar'];
                    $buscaMes = $_POST['mesModificar'];
                    $buscaTipoNota = $_POST['tipoNotaModificar'];

                    $consultaBuscaNota = $colegio->query("SELECT notas.nota, mes.mes, notas.tipo_nota, notas.id_nota FROM notas LEFT JOIN alumno ON notas.id_alumno = alumno.id
            LEFT JOIN mes ON notas.mes = mes.mes WHERE alumno.id = '$buscaId' AND mes.mes = (SELECT mes FROM mes WHERE id='$buscaMes') AND notas.tipo_nota=(SELECT tipoNota FROM tipo_nota WHERE id='$buscaTipoNota')");
                    while ($rowBuscaNota = $consultaBuscaNota->fetch()) {
                        echo "<tr>";
                        echo "<td>";
                        echo "<label for=''>Nota:</label>";
                        echo "<input style= color:black; type='text' name='notaModifica' value='$rowBuscaNota[0]'>";
                        echo "</td>";
                        echo "<td>";
                        echo "<label for=''>Tipo:</label>";
                        echo "<select name='tipoNotaModifica' class='selectpicker'>";
                        mostrar($colegio,$consultaTipoNota, $tipoNota);
                        echo "</select>";
                        echo "</td>";
                        echo "<td>";
                        echo "<label for=''>Mes:</label>";
                        echo "<select name='mesModifica' class='selectpicker'>";
                        mostrar($colegio,$consultaMes, $mes);
                        echo "</select>";
                        echo "</td>";
                        echo "<td>";
                        echo "<button type='submit' class='btn btn-info' name='modificar' value='$rowBuscaNota[3]'>Modificar</button><br><br>";
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </form>
        <p style="color: white;"><?= $mensajeM ?></p><br><br>
        <form action="" method="post">
            <button name="ver" class="btn btn-warning" type="submit">Ver Notas</button>
        </form>
    </div>
</body>

</html>