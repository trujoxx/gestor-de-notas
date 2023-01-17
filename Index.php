<?php
$colegio = new PDO("mysql:host=localhost;dbname=colegio", "root", "");
$mensajeL = "";
if (isset($_POST['log'])) {
    $userLogin = $_POST["userLogin"];
    $passwordLogin = $_POST["passwordLogin"];

    $credenciales = $colegio->query("SELECT contraseña FROM colegio.login WHERE usuario= '$userLogin'");
    $row = $credenciales->fetch();
    if (empty($userLogin) || empty($passwordLogin)) {
        $mensajeL = "Rellene todos los campos";
    } else {
        if ($passwordLogin == $row['contraseña']) {
            header("Location: clase.php");
        } else $mensajeL = "Usuario o contraseña introducidos son incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilo.css">
    <title>Log In</title>
</head>

<body>
    <div class="login-body">
        <div class="login-box">
            <img src="img/birrete.png" class="logo">
            <h1>Log in</h1>
            <form action="" method="post">
                <label for="">Usuario</label>
                <input type="text" name="userLogin" placeholder="Usuario" id="">

                <br>
                <br>

                <label for="">Contraseña</label>
                <input type="password" name="passwordLogin" placeholder="Contraseña" id="">

                <br>
                <br>

                <button type="submit" name="log">Iniciar sesión</button>

            </form>
            <br>
            <p><?= $mensajeL ?></p>
        </div>
    </div>
</body>

</html>