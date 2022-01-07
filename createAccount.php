<?php include('header.php')?>

<div class="container text-center align-content-center" >
    <div class="container text-center align-content-center" >
        <form class="card mx-auto    nocolor-card" style="width: 20rem;" method="POST" name="form">
            <h3>Inscrire Joueur</h3>
            <div class="form-group" >
                <label for="pseudo">Pseudo :</label>
                <input type="text" class="form-control" placeholder="Pseudo" name="pseudo" required/>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" class="form-control" placeholder="***********" required/>
            </div>
            <div class="form-group">
                <button type="submit" name="CREATE" value="connexion" class="btn btn-primary">Création</button>

            </div>
        </form>
    </div>
    <?php
    $player=$_SESSION['pseudo'];
    $dbname = 'mabase.db';
    $base = new SQLite3($dbname);

    if(isset($_POST['CREATE'])){
        $pseudo=$_POST['pseudo'];
        $password=password_hash($_POST['password'],PASSWORD_DEFAULT);
        if(!$base->query("SELECT * FROM Utilisateur WHERE pseudo='$pseudo'")->fetchArray()['pseudo']){
            $base->exec("INSERT INTO Utilisateur (pseudo,password) VALUES ('$pseudo','$password')");
            echo "<h2>Joueur ".$_POST['pseudo']." bien inscrit</h2>";
        };

    }
    ?>
</div>