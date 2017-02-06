<?php

namespace OwlyCode\NeuralEvolution;

class Rand
{
    public static function getFloat()
    {
        return mt_rand() / mt_getrandmax();
    }

    public static function getFloatNegative()
    {
        $max = mt_getrandmax();

        return mt_rand() / (mt_getrandmax() / 2) - 1;
    }
}
