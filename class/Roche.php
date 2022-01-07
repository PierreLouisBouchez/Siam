<?php


class Roche extends Pion{
    public function __toString(){
        return "<img src=\"img/rocher.png\" alt='roche'/>";
    }

    public function toSQL(){
        return "M";
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