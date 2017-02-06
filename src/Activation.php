<?php

namespace OwlyCode\NeuralEvolution;

class Activation
{
    public static function sigmoid($x)
    {
        return 1 / ( 1 + exp(-$x) );
    }

    public static function flat($x)
    {
        return $x;
    }
}
