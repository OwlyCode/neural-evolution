<?php

namespace OwlyCode\NeuralEvolution;

class Ladder extends \ArrayObject
{
    private $maxSize;

    public function __construct(array $organisms, $maxSize = 3)
    {
        parent::__construct($organisms);

        $this->maxSize = $maxSize;
        $this->sort();
        $this->truncate();
    }

    public function add(Organism $organism)
    {
        $this[] = $organism;

        $this->sort();
        $this->truncate();
    }

    public function getAll()
    {
        return $this->getArrayCopy();
    }

    private function sort()
    {
        $this->uasort(function (Organism $a, Organism $b) {
            return $b->getFitness() <=> $a->getFitness();
        });
    }

    private function truncate()
    {
        $this->exchangeArray(array_slice($this->getArrayCopy(), 0, $this->maxSize));
    }
}
