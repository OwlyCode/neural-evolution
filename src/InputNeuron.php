<?php

namespace OwlyCode\NeuralEvolution;

class InputNeuron extends Neuron
{
    public function __construct($name = null)
    {
        $this->name = $name ?? uniqid();
        $this->layer = 0;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function getRandomInput()
    {
        return null;
    }

    public function stimulate()
    {
        return;
    }

    public function export()
    {
        return [
            'name' => $this->name,
            'layer' => $this->layer
        ];
    }
}