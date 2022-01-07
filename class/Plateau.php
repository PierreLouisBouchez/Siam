<?php

include("Pion.php");
include("Elephant.php");
include("Rhinoceros.php");
include ("Stop.php");
include ("Roche.php");
class Plateau
{
    private $erreur="";
    private $pion=array();
    private $elephant=array();
    private $rhinoceros=array();
    private $current;

    public function __construct() {
        for($i = 0 ; $i < 5; $i++)
            $this->pion[$i] = array(null,null,null,null,null);
        for($i=0;$i<5;$i++){
            $this->elephant[$i]=new Elephant(0,false);
            $this->rhinoceros[$i]=new Rhinoceros(0,false);
        }
    }


    public function __toString(){
        $res="<table class='plateau'>";

        for ($i=0;$i<5;$i++){
            $res .= "<tr>";
            for ($j=0;$j<5;$j++) {
                $pion=$this->pion[$i][$j];
                if($pion != null){
                    $name=$pion;
                    if($pion->getType()){
                        $dir=$pion->getDirection();
                        $res .= "<td value='$i$j$dir' ";
                        $res.="class='";
                        $l=false;
                        if($l=($this->current%2==0 && $pion->getType()=="rhino")||$this->current%2!=0 && $pion->getType()=="elephant"){
                            $res .= " pion  red ";
                        }
                        if($i==0 || $j==0 || $i == 4 || $j==4){
                            $res .= " entry ";
                        }
                        $res .= "'";

                        $res.="><label><input type='radio'";
                        if($l){
                            $res.=" name='pion'";
                           }
                        else{
                            $res.=" name='entry'";
                        }
                        $res.="value='$i$j".$dir."' style='display: none'><img src='$name' alt='pion'></label></td>";
                    }else{
                        $res .= "<td value='$i$j' ";
                        $res.=" class='";
                        if(($i==0 || $j==0 || $i == 4 || $j==4) && $name->getNameOfClass()=="Roche"){
                            $res .= "entry";
                        }
                        $res .= "'";
                        if($name->getNameOfClass()=="Stop" ) {
                            $res .= ">$name</td>";
                        }else{
                            $res .= "><label><input type='radio' name='entry' value='$i$j' style='display: none'>$name</label></td>";
                        }
                    }
                }else{
                    $res .= "<td value='$i$j'";
                    if($i==0 || $j==0 || $i == 4 || $j==4){
                        $res.="class='spawn' ><label style='width: 100%'><input name='spawn'";
                    }else{
                        $res.="><label style='width: 100%'><input name='other'";
                    }
                    $res.=" type='radio' value='$i$j"."0"."' style='display: none'><img src='/SIAM/img/vide.png' style='width: 100%'></td>";
                }
            }
            $res.= "</tr>";
        }
        $res.="</table>";
        return $res;
    }

    public function showPion(){
        $res="";
        if($this->current%2==0){
            foreach ($this->rhinoceros as $elem){
                $res .= "<label  ><input  type='radio' name='reserve' style='display: none' ><img id='reserve' class='red'src='$elem'></label>";

            }} else{
            foreach ($this->elephant as $elem){
                $res .= "<label  ><input  type='radio' name='reserve' style='display: none'><img id='reserve' class='red'src='$elem'></label>";

            }
        }

        return $res;
    }

    public function move($x,$y,$angle,$dir){
        $currendir=$this->pion[$x][$y]->getDirection();
        $dx=0;
        $dy=0;
        switch ($dir){
            case 0:
                $dx-=1;
                break;
            case 1:
                $dy+=1;
                break;
            case 2:
                $dx+=1;
                break;
            case 3:
                $dy-=1;
                break;
        }
        if($x+$dx >-1 && $x+$dx <5 && $y+$dy >-1 && $y+$dy <5){
            if(!$this->pion[$x+$dx][$y+$dy]){
                $this->pion[$x+$dx][$y+$dy]=$this->pion[$x][$y];
                $this->pion[$x+$dx][$y+$dy]->setDirection($angle);
                $this->pion[$x][$y]=null;
                return true;
            }else if($dir==$currendir) {
                $res = 0;
                $i=1;
                $roche=0;
                while(($x + ($dx * $i) >-1 && $x + ($dx * $i) <5 && $y + ($dy * $i) >-1 && $y + ($dy * $i) <5) && $this->pion[$x + ($dx * $i)][$y + ($dy * $i)]!=null){
                    if($this->pion[$x + ($dx * $i)][$y + ($dy * $i)]->getDirection()!=null){
                        if($currendir==0 || $currendir==2) {
                            if ($this->pion[$x + ($dx * $i)][$y + ($dy * $i)]->getDirection() == 0) {
                                $res--;
                            } elseif ($this->pion[$x + ($dx * $i)][$y + ($dy * $i)]->getDirection() == 2) {
                                $res++;
                            }
                        }elseif ($currendir==1 || $currendir==3){
                            if ($this->pion[$x + ($dx * $i)][$y + ($dy * $i)]->getDirection() == 1) {
                                $res++;
                            } elseif ($this->pion[$x + ($dx * $i)][$y + ($dy * $i)]->getDirection() == 3) {
                                $res--;
                            }
                    }}else{
                        $roche++;
                    }
                    $i++;
                }
                $res*=-($dx+$dy);
                if($res<=0 && ($res+$roche)<=1) {
                    while ($i >0) {
                        if(($x + ($dx * $i) >-1 && $x + ($dx * $i) <5 && $y + ($dy * $i) >-1 && $y + ($dy * $i) <5)){
                            $this->pion[$x + ($dx * ($i))][$y + ($dy * ($i))] = $this->pion[$x + ($dx * ($i-1))][$y + ($dy * ($i-1))];
                        }elseif ($this->pion[$x + ($dx * ($i-1))][$y + ($dy * ($i-1))]->getType()==null){
                            for($j=$i-1;$j>0;$j--){
                                if($this->pion[$x + ($dx * ($j-1))][$y + ($dy * ($j-1))]->getDirection()==$currendir){
                                    if($this->pion[$x + ($dx * ($j-1))][$y + ($dy * ($j-1))]->getType()=="rhino"){
                                        $this->setCurrent(-11);
                                    }else {
                                        $this->setCurrent(-10);
                                    }
                                    $j = 0;
                                }
                            }
                        }
                        $i--;
                    }
                    $this->pion[$x + ($dx * ($i + 1))][$y + ($dy * ($i + 1))] = $this->pion[$x + ($dx * $i)][$y + ($dy * $i)];
                    $this->pion[$x + ($dx * ($i))][$y + ($dy * ($i))] = null;
                    return true;
                }
            }
        }
        return false;
    }

    public function moveAtSpawn($x,$y,$pi,$dir){
        $dx=0;
        $dy=0;
        switch ($dir){
            case 0:
                $dx-=1;
                break;
            case 1:
                $dy+=1;
                break;
            case 2:
                $dx+=1;
                break;
            case 3:
                $dy-=1;
                break;
        }
        if($x+$dx >-1 && $x+$dx <5 && $y+$dy >-1 && $y+$dy <5){
                $res = 0;
                $i=0;
                $roche=0;

                while(($x + ($dx * $i) >-1 && $x + ($dx * $i) <5 && $y + ($dy * $i) >-1 && $y + ($dy * $i) <5) && $this->pion[$x + ($dx * $i)][$y + ($dy * $i)]!=null){
                    if($this->pion[$x + ($dx * $i)][$y + ($dy * $i)]->getDirection()!=null){
                        if($dir==0 || $dir==2) {
                            if ($this->pion[$x + ($dx * $i)][$y + ($dy * $i)]->getDirection() == 0) {
                                $res--;
                            } elseif ($this->pion[$x + ($dx * $i)][$y + ($dy * $i)]->getDirection() == 2) {
                                $res++;
                            }
                        }elseif ($dir==1 || $dir==3){
                            if ($this->pion[$x + ($dx * $i)][$y + ($dy * $i)]->getDirection() == 1) {
                                $res++;
                            } elseif ($this->pion[$x + ($dx * $i)][$y + ($dy * $i)]->getDirection() == 3) {
                                $res--;
                            }
                        }}else{
                        $roche++;
                    }
                    $i++;
                }
                $res*=-($dx+$dy);
                if($res<=0 && ($res+$roche)<=1) {
                    while ($i !=0) {
                        if(($x + ($dx * $i) >-1 && $x + ($dx * $i) <5 && $y + ($dy * $i) >-1 && $y + ($dy * $i) <5)){
                            $this->pion[$x + ($dx * ($i))][$y + ($dy * ($i))] = $this->pion[$x + ($dx * ($i-1))][$y + ($dy * ($i-1))];
                        }elseif ($this->pion[$x + ($dx * ($i-1))][$y + ($dy * ($i-1))]->getType()==null){
                            for($j=$i-1;$j>0;$j--){
                                if($this->pion[$x + ($dx * ($j-1))][$y + ($dy * ($j-1))]->getDirection()==$dir){
                                    if($this->pion[$x + ($dx * ($j-1))][$y + ($dy * ($j-1))]->getType()=="rhino"){
                                        $this->setCurrent(-11);
                                    }else {
                                        $this->setCurrent(-10);
                                    }
                                    $j = 0;
                                }
                            }

                        }
                        $i--;
                    }
                    $this->pion[$x + ($dx * ($i + 1))][$y + ($dy * ($i + 1))] = $this->pion[$x + ($dx * $i)][$y + ($dy * $i)];
                    $this->pion[$x + ($dx * $i)][$y + ($dy * $i)] = $pi;
                    return true;
            }
        }
        return false;
    }


    /**
     * @return string
     */
    public function getErreur()
    {
        return $this->erreur;
    }




    public function save(){
        if($this->current==2){
            $this->pion[0][2]=null;
            $this->pion[4][2]=null;

        }
        $res="";
        for ($i=0;$i<5;$i++) {
            for ($j = 0; $j < 5; $j++) {
                if($this->pion[$i][$j] !=null ){
                    $res.=$this->pion[$i][$j]->toSQL();
                }else{
                    $res.="4";
                }
            }
        }
        return $res;
        
    }

    /**
     * @return mixed
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * @param mixed $current
     */
    public function setCurrent($current)
    {
        $this->current = $current;
    }




    public function spawn($x,$y,$p){
        $this->pion[$x][$y]=$p;
    }

    public function despawn($x,$y){
        $this->pion[$x][$y]=null;

    }
    public function rotate($x,$y,$angle){
        $this->pion[$x][$y]->setDirection($angle);

    }

    public function getPlateau(){
        return $this->pion;
    }

    public function load($res){
        $curr=0;
        for ($i=0;$i<5;$i++) {
            for ($j = 0; $j < 5; $j++) {
                if($res[$curr]=="M"){
                    $this->pion[$i][$j]=new Roche();
                }else if($res[$curr]=="S"){
                    $this->pion[$i][$j]=new Stop();
                }else if($res[$curr]=='E'){
                    $this->pion[$i][$j]=new Elephant($res[++$curr],true);
                    array_pop($this->elephant);
                }else if($res[$curr]=='R'){
                    $this->pion[$i][$j]=new Rhinoceros($res[++$curr],true);
                    array_pop($this->rhinoceros);
                }
                $curr++;
            }
        }
        return $res;
    }

     /*
     *
     * 0 0 0 0 0
     * 0 0 0 0 0
     * 0 M M M 0
     * 0 0 0 0 0
     * 0 0 0 0 0
     *
     */
}