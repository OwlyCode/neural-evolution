<?php

namespace OwlyCode\NeuralEvolution\Trial;

use OwlyCode\NeuralEvolution\Organism;
use OwlyCode\NeuralEvolution\TrialInterface;

class OrTrial implements TrialInterface
{
    public function attempt(Organism $organism)
    {
        $score = 4;

        list ($result) = $organism->stimulate([1, 1]);
        $score -= abs(1 - $result);

        list ($result) = $organism->stimulate([1, 0]);
        $score -= abs(1 - $result);

        list ($result) = $organism->stimulate([0, 1]);
        $score -= abs(1 - $result);

        list ($result) = $organism->stimulate([0, 0]);
        $score -= abs(0 - $result);

        return $score;
    }

    public function getMaxScore()
    {
        return 3.95;
    }
}
