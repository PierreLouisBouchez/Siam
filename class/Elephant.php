<?php


class Elephant extends Pion{
    private  $onplateau;
        private  $direction;
    public function __construct($direction, $onplateau){
        $this->direction = $direction;
        $this->onplateau = $onplateau;
    }
    public function __toString(){
        $res="";
        switch ($this->direction){
            case 0:
                $res.="img/10.gif";
                break;
            case 1:
                $res.= "img/11.gif";
                break;
            case 2:
                $res.= "img/12.gif";
                break;
            case 3:
                $res.= "img/13.gif";
                break;
        }
        return $res;
    }

    public function getType(){
        return "elephant";
    }

    public function toSQL(){
        return "E".$this->direction;
    }

    public function getDirection(){
        return $this->direction;
    }

    public function setDirection($direction)
    {
        $this->direction = $direction;
    }


}