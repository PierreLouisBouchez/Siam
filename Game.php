<html>
<body>
<?php
include "header.php";
include("class/Plateau.php");
$dbname = 'mabase.db';
$base = new SQLite3($dbname);
$id = $_GET['id'];
$player = $_SESSION['pseudo'];
$select = $base->query("SELECT player1,player2,plateau,current FROM Game WHERE ID=$id");
$plateau = new Plateau();
$row=$select->fetchArray(SQLITE3_ASSOC);
$player1=$row['player1'];
$player2=$row['player2'];
$plateau->load($row['plateau']);
$plateau->setCurrent($row['current']);
$curr=$row['current'];


if(isset($_POST['JOUER'])){
    setcookie('message',"");
    if(isset($_POST['reserve']) && isset($_POST['spawn']) ){
        $x = $_POST['spawn'][0];
        $y = $_POST['spawn'][1];
        $angle = $_POST['spawn'][2];
        if($plateau->getCurrent()%2==0){
            $plateau->spawn($x, $y, new Rhinoceros($angle, true));
        }else{
            $plateau->spawn($x, $y, new Elephant($angle, true));
        }
        $plateau->setCurrent($plateau->getCurrent()+1);
        $matrice=$plateau->save();
        $curr=$plateau->getCurrent();
        $select = $base->exec("UPDATE Game SET plateau = '$matrice' , current = '$curr' WHERE ID=$id");
    }else if(isset($_POST['reserve']) && !isset($_POST['spawn']) && (isset($_POST['pion']) || isset($_POST['entry']))){
        $x=0;
        $y=0;
        $dir=0;
        if(isset($_POST['pion'])){
            $x = $_POST['pion'][0];
            $y = $_POST['pion'][1];

        }else{
            $x = $_POST['entry'][0];
            $y = $_POST['entry'][1];
        }
        if(isset($_POST['move'])){
            $dir=$_POST['move'];
        }else{
            if($x==0){
                $dir=2;
            }else if($x==4){
                $dir=0;
            }else if($y==0){
                $dir=1;
            }else{
                $dir=3;
            }

        }
        if($curr%2==0){
            $pion=new Rhinoceros($dir,true);
        }else{
            $pion=new Elephant($dir,true);
        }
        if($plateau->moveatspawn($x,$y,$pion,$dir)){
            setcookie('message',$plateau->getErreur());
            $plateau->setCurrent($plateau->getCurrent()+1);
            $matrice=$plateau->save();
            $curr=$plateau->getCurrent();
            $select = $base->exec("UPDATE Game SET plateau = '$matrice' , current = '$curr' WHERE ID=$id");
        }else{
            setcookie('message',"Entrer impossible!");
        }




    }else if(isset($_POST['pion'])  && !isset($_POST['reserve'])  && !isset($_POST['spawn']) ){
        $x = $_POST['pion'][0];
        $y = $_POST['pion'][1];
        $angle = $_POST['pion'][2];
        if(isset($_POST['move'])){
            $dir=$_POST['move'];
            if($plateau->move($x,$y,$angle,$dir)){
                $plateau->setCurrent($plateau->getCurrent()+1);
                $matrice=$plateau->save();
                $curr=$plateau->getCurrent();
                $select = $base->exec("UPDATE Game SET plateau = '$matrice' , current = '$curr' WHERE ID=$id");
            }else{
                setcookie('message',$plateau->getErreur());
            }
            setcookie('message',$plateau->getErreur());
        }else if($angle != $plateau->getPlateau()[$x][$y]->getDirection()){
            $plateau->rotate($x,$y,$angle);
            $plateau->setCurrent($plateau->getCurrent()+1);
            $matrice=$plateau->save();
            $curr=$plateau->getCurrent();
            $select = $base->exec("UPDATE Game SET plateau = '$matrice' , current = '$curr' WHERE ID=$id");

        }
    }
    header('Refresh:0');
    exit();
}else if(isset($_POST['despawn']) && isset($_POST['pion']) ){
    $x = $_POST['pion'][0];
    $y = $_POST['pion'][1];
    if($x==0 || $y==0 || $x == 4 || $y==4) {
        $plateau->despawn($x, $y);
        $plateau->setCurrent($plateau->getCurrent()+1);
        $matrice=$plateau->save();
        $curr=$plateau->getCurrent();
        $select = $base->exec("UPDATE Game SET plateau = '$matrice' , current = '$curr' WHERE ID=$id");
    }else{
        setcookie('message',"Impossible de sortir cette piece");
    }
    header('Refresh:0');
    exit();
}

?>

<div class="container" style="<?php
        if($row['current']>=0){
            if(($row['current']%2==0 && $_SESSION['pseudo']==$row['player2'])||($row['current']%2!=0 && $_SESSION['pseudo']==$row['player1'])) {

                if (!isset($_SESSION['admin'])) {
                    echo "pointer-events: none";
                }
            }
            }else{
            echo "pointer-events: none";
        }?>">
    <form method="post" >
        <div class="row center">
            <div class="col-lg-2 col-12">
                <?php
                echo $plateau->showPion();

                ?>
            </div>
            <div class="col-lg-7 offset-lg-0 col-10 offset-1">
                <?php
                if($row['current']==-10){
                     echo "<h4>PARTIE TERMINER $player1 a gagné !! </h4>";
                }else if($row['current']==-9){
                    echo "<h4>PARTIE TERMINER  $player2 a gagné !! </h4>";

                }
                if(isset($_COOKIE['message'])){
                    echo "<h4>".$_COOKIE['message']."</h4>";
                }
                if($row['current']>=0){
                    echo "<h4>Au tour de ";
                    if($curr%2==0){
                        echo "$player1";
                    }else {
                        echo "$player2";
                    }
                }
                 echo "</h4>";
                echo $plateau;
                ?>
            </div>
            <div class="col-lg-3 offset-lg-0 col-6 offset-3 ">
                <div class="row">
                    <label class="col-4 offset-4 up">
                        <input type='radio' name='move' value='0' style='display: none'>
                        <img  src="img/up.png" alt="">
                    </label>
                </div>
                <div class="row">
                    <label class="col-4 left">
                        <input type='radio' name='move' value='3' style='display: none'>
                        <img src="img/left.png" alt="">
                    </label>
                    <label class="col-4 ">
                        <img class="rotate" src="img/rotate.png" alt="" >
                    </label>
                    <label class="col-4 right">
                        <input type='radio' name='move' value='1' style='display: none'>
                        <img  src="img/right.png" alt="">
                    </label>
                </div>
                <div class="row">
                    <label class="col-4 offset-4 down">
                        <input type='radio' name='move' value='2' style='display: none'>
                        <img src="img/down.png" alt="">
                    </label>
                </div>
            <button class="btn btn-light col-12" type="submit" name="JOUER" value="jouer" style="margin-top: 1rem;margin-bottom:1rem;">Valider</button>
                <button class="btn btn-light col-12" type="submit" name="despawn" value="despawn" style="margin-top: 1rem;margin-bottom:1rem;">Retirer</button>
            <button class="btn btn-light col-12" type="submit" name="CANCEL" value="cancel">Annuler</button>
            </div>
        </div>
    </form>
</div>

<script src="jquery/jquery.js"></script>
<script type="text/javascript" src="js/script.js?v5"></script>
</body>
</html>
