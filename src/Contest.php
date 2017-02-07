<?php

namespace OwlyCode\NeuralEvolution;

class Contest
{
    private $maxConcurrentSpecies;

    private $trial;

    private $ladder;

    private $participants;

    private $generation;

    private $callable;

    public function __construct(array $participants, TrialInterface $trial, $maxConcurrentSpecies = 5, $generation = 0)
    {
        $this->maxConcurrentSpecies = $maxConcurrentSpecies;
        $this->trial = $trial;
        $this->ladder = new Ladder($participants, $maxConcurrentSpecies);
        $this->participants = $participants;
        $this->generation = $generation;
    }

    public function onEachIteration(callable $callable = null)
    {
        $this->callable = $callable;

        return $this;
    }

    public function evolve($mutationRate = 3, $population = 1000)
    {
        $participantsCount = count($this->participants);

        for ($i=0; $i<$population; $i++) {
            $child = $this->participants[$i % $participantsCount]->clone();
            for ($j=0; $j<$mutationRate; $j++) {
                Mutator::mutate($child);
            }
            $child->setFitness($this->trial->attempt($child));

            $this->ladder->add($child);
        }

        $selected = $this->ladder->getAll();

        foreach ($selected as $organism) {
            $organism->optimize();
            $organism->reset();

            if ($organism->getFitness() > $this->trial->getMaxScore()) {
                return $organism;
            }
        }

        if ($this->callable) {
            call_user_func($this->callable, $selected, $this->generation);
        }

        return (new Contest($selected, $this->trial, $this->maxConcurrentSpecies, ++$this->generation))
            ->onEachIteration($this->callable)
            ->evolve($mutationRate, $population)
        ;
    }
}
