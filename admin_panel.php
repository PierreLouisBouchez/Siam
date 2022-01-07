<html>
<body>
<?php include('header.php')?>

<div class="container text-center " >
    <div class="row">
        <div class=" col-lg-3 col-12">
            <?php
            $_SESSION['admin']="ok";
            $dbname = 'mabase.db';
            $base = new SQLite3($dbname);
            $mytable = "Utilisateur";
            $select = $base->query("SELECT * FROM $mytable");
            $all = $base->query("SELECT * FROM Game");
            ?>
            <h3 style='color:#FCFBDE'>Tous les inscrits</h3>
            <table class='container table' style='width: 30rem'>
            <thead><tr><th scope="col" align="center" style='color:#FCFBDE;'>Pseudo</th></tr></thead>
            <tbody>
            <?php
            while ($row = $select->fetchArray(SQLITE3_ASSOC)) {
            echo "<tr><td scope='row' align=\"center\" style='color:#FCFBDE;'>".$row['pseudo']."</td></tr>";
            }
            ?>
            </tbody></table>
        </div>
        <div class=" col-lg-8 offset-lg-1 col-12">
        <h3 style='color:#FCFBDE'>Toutes les Parties</h3>
        <table class='container table' style='width: 30rem'>
        <thead><tr><th scope=\"col\" ></th><th scope="col" style='color:#FCFBDE;'>Créateur</th><th scope="col" style='color:#FCFBDE;'>Adversaire</th><th scope=\"col\"></th></tr></thead>
        <tbody><form method='POST'>
            <?php
        while ($row = $all->fetchArray(SQLITE3_ASSOC)) {
            $id=$row['ID'];
            echo "<tr><td scope=\"row\" style='color:#FCFBDE;'><a type='button' class='btn btn-light' href='Game.php?id=$id'>Jouer</a></td><td style='color:#FCFBDE;'>" . $row['player1'] ."<td style='color:#FCFBDE;'>".$row['player2']. "</td><td><button type='submit' name='delete' value='$id' class='btn btn-light'>Supprimer</button></td></tr>";
        }
        echo "</form></tbody></table>";

        if(isset($_POST['delete'])){
            $id=$_POST['delete'];
            $base->exec("DELETE FROM Game WHERE ID IS $id");
            header('Refresh:0');
        }
        ?>
        </div>
    </div>
</div>
</body>
</html>