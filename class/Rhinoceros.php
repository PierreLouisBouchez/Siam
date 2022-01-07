<?php


class Rhinoceros extends Pion{
    private  $onplateau;
    private $direction;
    public function __construct($direction, $onplateau)
    {
        $this->direction = $direction;
        $this->onplateau = $onplateau;
    }

    public function __toString(){
        $res="";
        switch ($this->direction){
            case 0:
                $res.="img/20.gif";
                break;
            case 1:
                $res.= "img/21.gif";
                break;
            case 2:
                $res.= "img/22.gif";
                break;
            case 3:
                $res.= "img/23.gif";
                break;
        }
        return $res;
    }

    public function getType(){
        return "rhino";
    }

    public function toSQL(){
        return "R".$this->direction;
    }

    /**
     * @return mixed
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param mixed $direction
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
    }

}