<?php


class Stop extends Pion{
    public function __toString(){
        return "<img src=\"img/stop.png\" alt='stop'/>";
    }

    public function toSQL(){
        return "S";
    }
    public function getType(){
        return null;
    }
    public function getDirection(){
        return null;
    }
    public function setDirection($direction)
    {
        return null;
    }
    public function getNameOfClass()
    {
        return static::class;
    }
}