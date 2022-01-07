<?php
session_start();
$dbname = 'mabase.db';
$base = new SQLite3($dbname);
$mytable = "Utilisateur";
if ($result = $base->exec("CREATE TABLE IF NOT EXISTS $mytable (ID INTEGER PRIMARY KEY AUTOINCREMENT ,pseudo text,password text)")) {
    if (!$base->query("SELECT * FROM $mytable WHERE pseudo='admin'")->fetchArray()['pseudo']) {
        $base->exec("INSERT INTO $mytable (pseudo,password) VALUES ('admin','" . password_hash('admin', PASSWORD_DEFAULT) . "')");
    }

}
$base->exec("CREATE TABLE IF NOT EXISTS Game(ID INTEGER PRIMARY KEY AUTOINCREMENT ,player1 text,player2 text,plateau longtext,current int)");


if (isset($_POST['SEND'])) {
    $pseudo = $_POST['pseudo'];
    $password = $_POST['password'];
    $select = $base->query("SELECT password FROM $mytable WHERE pseudo IS '$pseudo'");
    $hash = $select->fetchArray()[0];
    if (password_verify($password, $hash)) {

        $_SESSION['pseudo'] = $pseudo;
        header('Location: main.php');

    }
}
?>
<html lang="fr">
<head>
    <title>SIAM</title>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon">
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="align-content-center">
<div class="container text-center align-content-center" >
    <img src="img/logo.gif" alt="logo" style="padding-bottom: 3rem;padding-top:  3rem;width: 15rem;">
    <form class="card mx-auto nocolor-card" style="width: 20rem;" method="POST" name="form">
            <div class="form-group" >
                <label for="pseudo">Pseudo :
                <input type="text" class="form-control" placeholder="Pseudo" name="pseudo" required/>
                </label>
            </div>
        <div class="form-group">
                <label for="password">Mot de passe :
                <input type="password" name="password" class="form-control" placeholder="***********" required/>
            </label>
            </div>
        <div class="form-group">
            <button type="submit" name="SEND" value="connexion" class="btn btn-primary">Connexion</button>
        </div>
    </form>
</div>

<script src="jquery/jquery.slim.min.js"></script>
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
