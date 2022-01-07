<?php
session_start();
if(!isset($_SESSION['pseudo']) ){
    header('Location:index.php');
    exit();
}


if(isset($_POST['EXIT'])){
    session_destroy();
    header('Location:index.php');

}


?>
<head>
    <title>SIAM</title>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<nav class="navbar navbar-expand-md  navbar-dark">
    <img src="img/logo.gif" alt="logo">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <form method="post">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="main.php">Menu</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="password.php">Modifier Mot de Passe</a>
            </li>
            <?php
                if($_SESSION['pseudo']=="admin"){
                    echo "<li><a class=\"nav-link\" href=\"createAccount.php\">Création de compte</a></li>";
                    echo "<li><a class=\"nav-link\" href=\"admin_panel.php\">Panneaux d'administration</a></li>";
                }
            ?>
            <li class="nav-item">
                <input class="btn btn-danger" type="submit" class="nav-link" name="EXIT" value="Déconnecter">
            </li>
        </ul>
        </form>
    </div>

</nav>