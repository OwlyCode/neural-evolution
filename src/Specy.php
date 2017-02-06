<?php

namespace OwlyCode\NeuralEvolution;

class Specy
{
    private $parent;

    private $tickFunc;

    private $generation;

    public function __construct(Organism $parent, callable $tickFunc = null, int $generation = 0)
    {
        $this->parent = $parent;
        $this->tickFunc = $tickFunc;
        $this->generation = $generation;
    }

    public function evolve(TrialInterface $trial, $mutationRate = 3, $population = 1000)
    {
        $winner = $this->parent;
        $winnerScore = $trial->attempt($this->parent);

        for ($i=0; $i<$population; $i++) {
            $child = $this->parent->clone();
            for ($j=0; $j<$mutationRate; $j++) {
                Mutator::mutate($child);
            }
            $score = $trial->attempt($child);
            if ($winnerScore < $score) {
                $winner = $child;
                $winnerScore = $score;
            }
        }

        $winner->optimize();
        $winner->reset();

        if ($winnerScore >= $trial->getMaxScore()) {
            return $winner;
        }

        if ($this->tickFunc) {
            call_user_func($this->tickFunc, $winner, $winnerScore, $this->generation);
        }

        return (new Specy($winner, $this->tickFunc, $this->generation + 1))->evolve($trial, $mutationRate, $population);
    }
}
