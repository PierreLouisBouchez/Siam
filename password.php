<?php include('header.php')?>

<div class="container text-center align-content-center" >
    <form class="card mx-auto    nocolor-card" style="width: 20rem;" method="POST" name="form">
        <h3>Modifier mot de passe</h3>
        <div class="form-group">
            <label for="password">Ancien mot de passe :</label>
            <input type="password" name="password" class="form-control" placeholder="***********" required/>
        </div>
        <div class="form-group">
            <label for="newpassword">Nouveau mot de passe :</label>
            <input type="password" name="newpassword" class="form-control" placeholder="***********" required/>
        </div>
        <div class="form-group">
            <button type="submit" name="UPDATE" value="connexion" class="btn btn-primary">Modifier</button>

        </div>
    </form>
    <?php
    $player=$_SESSION['pseudo'];
    $dbname = 'mabase.db';
    $base = new SQLite3($dbname);
    if(isset($_POST['UPDATE'])){
        $select = $base->query("SELECT password FROM Utilisateur WHERE pseudo IS '$player'");
        $hash = $select->fetchArray()[0];
        $password=$_POST['password'];
        $newpassword=password_hash($_POST['newpassword'],PASSWORD_DEFAULT);
        if(password_verify($password,$hash)){
            $stmt = $base->exec("UPDATE Utilisateur SET password = '$newpassword' WHERE pseudo = '$player'");
            echo "<h2>Mot de passe modifié</h2>";
        }
    }

    ?>
</div>