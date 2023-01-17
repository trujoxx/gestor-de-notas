<?php
$colegio = new PDO("mysql:host=localhost;dbname=colegio", "root", "");

if (isset($_POST['clase'])) $clase = $_POST['clase'];
if (isset($_POST['alumno'])) $alumno = $_POST['alumno'];
if (isset($_POST['mes'])) $mes = $_POST['mes'];
if (isset($_POST['tipoNota'])) $tipoNota = $_POST['tipoNota'];
if (isset($_POST['insertar'])) {
    header("Location: insertar.php");
}
if (isset($_POST['papelera'])) {
    $idNota = $_POST['papelera'];
    $eliminarNota = $colegio->query("DELETE FROM notas WHERE notas.id_nota = $idNota");
}



$consultaClase = "SELECT id,nombreClase FROM colegio.clase ORDER BY nombreClase DESC";
$consultaAlu = "SELECT id,concat(nombre,' ',apellidos) as nombreCompleto FROM colegio.alumno";
$consultaTipoNota = "SELECT id,tipoNota FROM colegio.tipo_nota";
$consultaMes = "SELECT * FROM colegio.mes ORDER BY mes.id";
$consultaTabla = $colegio->query("SELECT CONCAT(nombre,' ', apellidos), nombreClase, notas.nota, notas.tipo_nota, notas.mes, notas.id_nota FROM alumno LEFT JOIN notas ON notas.id_alumno = alumno.id");



if (isset($_POST['insertar'])) {
    header("Location: insertar.php");
}

function mostrar($base, $consulta, $parametro)
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
    <title>Notas</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Permanent+Marker&display=swap');
    </style>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>


    <link rel="stylesheet" href="css/estilo.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
</head>

<body>
    <div class="bg-image" style="background-image: url('img/fondoMostrar.jpg'); background-attachment: fixed; background-size: cover; height:auto; padding-bottom: 65vh;">
        <form action="" method='post'>
            <table class="table table-borderless">
                <tr>
                    <td>
                        <h3>Alumnado</h3>
                        <select name='alumno' class='selectpicker' data-show-subtext='true' data-live-search='true'>
                            <option value=0 >Todos</option>
                            <?php
                            mostrar($colegio, $consultaAlu, $alumno);
                            ?>
                        </select>
                    </td>
                    <td>
                        <h3>Grupo clase</h3>
                        <select name="clase" class="selectpicker">
                            <?php
                            mostrar($colegio, $consultaClase, $clase);
                            ?>
                        </select>
                    </td>

                    <td>
                        <h3>Calificaciones</h3>
                        <select name='tipoNota' class='selectpicker'>
                            <option value=0 >Todos</option>
                            <?php
                            mostrar($colegio, $consultaTipoNota, $tipoNota);
                            ?>
                        </select>
                    </td>
                    <td>
                        <h3>Meses</h3>
                        <select name='mes' class='selectpicker'>
                            <option value=0 >Todos</option>
                            <?php
                            mostrar($colegio, $consultaMes, $mes);
                            ?>
                        </select>
                    </td>
                    <td>
                        <br><br><br><button type='submit' class='btn btn-primary' name='filtrar'>Seleccionar</button>
                    </td>
                    <td>
                        <br><br><br><button type='submit' class='btn btn-warning' name='insertar'>Insertar</button>
                    </td>
                </tr>
            </table>
        </form>
        <br><br>
        <form action="" method="post">
            <table class="table">
                <tr>
                    <th>Alumno</th>
                    <th>Clase</th>
                    <th>Calificación</th>
                    <th>Tipo de Evaluación</th>
                    <th>Mes</th>
                </tr>
                <?php
                
                if (isset($_POST['filtrar'])) {
                    $clase = $_POST['clase'];
                    $alumno = $_POST['alumno'];
                    $mes = $_POST['mes'];
                    $tipoNota = $_POST['tipoNota'];
                    $cadena = "";
                    $cont = 0;

                    if ($clase != 9) {
                        $cadena = "WHERE alumno.nombreClase=(SELECT nombreClase FROM clase WHERE id='$clase')";
                        $cont = 1;
                    }
                    if ($alumno != 0) {
                        if ($cont == 1) {
                            $cadena .= " AND alumno.id =$alumno";
                        } else {
                            $cadena .= "WHERE alumno.id =$alumno";
                        }
                        $cont = 2;
                    }
                    if ($mes != 0) {
                        if ($cont == 2 || $cont == 1) {
                            $cadena .= " AND notas.mes=(SELECT mes FROM mes WHERE id='$mes')";
                        } else {
                            $cadena .= "WHERE notas.mes= (SELECT mes FROM mes WHERE id='$mes')";
                        }
                        $cont = 3;
                    }
                    if ($tipoNota != 0) {
                        if ($cont == 3 || $cont == 2 || $cont == 1) {
                            $cadena .= " AND notas.tipo_nota=(SELECT tipoNota FROM tipo_nota WHERE id='$tipoNota')";
                        } else {
                            $cadena .= "WHERE notas.tipo_nota=(SELECT tipoNota FROM tipo_nota WHERE id='$tipoNota')";
                        }
                    }
                    $consultaFiltrada = $colegio->query("SELECT CONCAT(nombre,' ', apellidos) as nombreCompleto, nombreClase, notas.nota, notas.tipo_nota, notas.mes, notas.id_nota FROM alumno 
            LEFT JOIN notas ON notas.id_alumno = alumno.id $cadena");

                    while ($row = $consultaFiltrada->fetch()) {
                        echo "<tr class='verNotas'>";
                        echo "<td>$row[0]</td>";
                        echo "<td>$row[1]</td>";
                        echo "<td>$row[2]</td>";
                        echo "<td>$row[3]</td>";
                        echo "<td>$row[4]</td>";
                        echo "<td><button name='papelera' class='btn btn-danger' value='$row[5]'><img src='img/papelera.png'/></button></td>";
                        echo "</tr>";
                    }
                } else {
                    while ($row = $consultaTabla->fetch()) {
                        echo "<tr class='verNotas'>";
                        echo "<td>$row[0]</td>";
                        echo "<td>$row[1]</td>";
                        echo "<td>$row[2]</td>";
                        echo "<td>$row[3]</td>";
                        echo "<td>$row[4]</td>";
                        echo "<td><button name='papelera' class='btn btn-danger' value='$row[5]'><img src='img/papelera.png'/></button></td>";
                        echo "</tr>";
                    }
                }


                ?>
            </table>
        </form>
    </div>
</body>

</html>