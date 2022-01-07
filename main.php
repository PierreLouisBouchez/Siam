<html>
<body class="align-content-center">
<?php include('header.php');?>
    <div class="container text-center align-content-center" >
        <form method="post">
            <button style="margin: 5rem;" type="submit" class="mx-auto btn btn-light" value="NEW" name="NEW" >Nouvelle Partie</button>
        </form>
        <div class="row">
        <?php
        unset($_SESSION['admin']);
        $player=$_SESSION['pseudo'];
        $dbname = 'mabase.db';
        $base = new SQLite3($dbname);
        if(isset($_POST['NEW'])){
            $base->exec("INSERT INTO Game (player1,plateau,current) VALUES ('$player','44S44444444MMM44444444S44',0)");

        }
        $mytable = "Utilisateur";
        $select = $base->query("SELECT * FROM Game WHERE player1='$player' OR player2='$player'");
        $joinable = $base->query("SELECT * FROM Game WHERE NOT player1='$player' AND player2 IS NULL");
        //echo "admin :".$_SESSION['admin'];
        ?>
            <div class="col-lg-6 col-12">
        <h3 style='color:#FCFBDE'>Vos Parties</h3>
        <table class=' table' style='width: 30rem'>
        <thead><tr><th scope="col" style='color:#FCFBDE;'>Jouer</th><th scope="col" style='color:#FCFBDE'>Créateur</th><th scope="col" style='color:#FCFBDE;'>Adversaire</th></tr></thead>
        <tbody>
        <?php
        while ($row = $select->fetchArray(SQLITE3_ASSOC)) {
            $id=$row['ID'];
            $current=$row['current'];
            $player1="";
            $player2="";
            if($current%2==0){
                $player1="current";
            }else{
                $player2="current";
            }
            echo "<tr><td scope='row' style='color:#FCFBDE;'><a type='button' class='btn btn-light' href='Game.php?id=$id'>Jouer</a></td><td class='$player1' >" . $row['player1'] . "</td><td class='$player2' >" . $row['player2'] . "</td></tr>";
        }
        ?>
        </tbody></table>

        </div><div class="col-lg-6 col-12">

        <h3 style='color:#FCFBDE'>Parties à Rejoindre</h3>
        <table class='table' style='width: 30rem'>
        <thead><tr><th scope="col" ></th><th scope="col" style='color:#FCFBDE'>Créateur</th></tr></thead>
        <tbody><form method='POST'>
        <?php
            while ($row = $joinable->fetchArray(SQLITE3_ASSOC)) {
            $id=$row['ID'];
            echo "<tr><td scope=\"row\" style='color:#FCFBDE;'><button type='submit' name='JOIN' value='$id' class='btn btn-light'>Rejoindre</button></td><td style='color:#FCFBDE;'>" . $row['player1'] ."<td style='color:#FCFBDE;'>".$row['player2']. "</td></tr>";
        }
        if(isset($_POST['JOIN'])){
            $id=$_POST['JOIN'];
            $stmt = $base->exec("UPDATE Game SET player2 = '$player' WHERE ID = '$id'");
            header('Location:main.php');
            exit();
        }


        ?>
        </form></tbody></table>

    </div>

</body>
</html>
