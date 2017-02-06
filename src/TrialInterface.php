<?php

namespace OwlyCode\NeuralEvolution;

interface TrialInterface
{
    public function getMaxScore();
    public function attempt(Organism $organism);
}
