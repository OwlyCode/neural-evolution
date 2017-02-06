<?php

namespace OwlyCode\NeuralEvolution;

class Synapse
{
    public $source;

    public $weight;

    public function __construct(Neuron $source, float $weight)
    {
        $this->source = $source;
        $this->weight = $weight;
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function getValue()
    {
        return $this->source->getState() * $this->weight;
    }

    public function export()
    {
        return [
            'weight' => $this->weight,
            'source' => $this->source->getName()
        ];
    }
}