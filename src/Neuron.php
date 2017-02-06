<?php

namespace OwlyCode\NeuralEvolution;

class Neuron
{
    private $activation;

    private $inputs;

    private $layer;

    protected $state;

    protected $name;

    public function __construct(string $activation, $layer = 1, $name = null)
    {
        $this->name = $name ?? uniqid();
        $this->activationName = $activation;
        $this->activation = ['OwlyCode\NeuralEvolution\Activation', $activation];
        $this->inputs = [];
        $this->layer = $layer;
    }

    public function getName()
    {
        return $this->name;
    }

    public function reset()
    {
        $this->state = 0;
    }

    public function export()
    {
        return [
            'name' => $this->name,
            'activation' => $this->activationName,
            'layer' => $this->layer,
            'inputs' => array_map(function ($synapse) {
                return $synapse->export();
            }, $this->inputs)
        ];
    }

    public function getInputs()
    {
        return $this->inputs;
    }

    public function hasInputs()
    {
        return count($this->inputs) > 0;
    }

    public function getRandomInput()
    {
        $inputs = array_merge([], $this->inputs);
        shuffle($inputs);

        return $inputs[0] ?? null;
    }

    public function stimulate()
    {
        $sum = 0;

        foreach ($this->inputs as $synapse) {
            $sum += $synapse->getValue();
        }

        $this->state = call_user_func($this->activation, $sum);
    }

    public function getLayer()
    {
        return $this->layer;
    }

    public function getState()
    {
        return $this->state;
    }

    public function attach(Synapse $synapse)
    {
        $this->inputs[] = $synapse;
    }

    public function purgeSynapses(Neuron $neuron)
    {
        $this->inputs = array_values(array_filter($this->inputs, function ($synapse) use ($neuron) {
            return $synapse->getSource() !== $neuron;
        }));
    }

    public function optimize()
    {
        $flatSynapses = [];

        foreach ($this->inputs as $input) {
            $flatSynapses[$input->getSource()->getName()] = ['source' => $input->getSource(), 'weight' => 0];
        }

        foreach ($this->inputs as $input) {
            $flatSynapses[$input->getSource()->getName()]['weight'] += $input->getWeight();
        }

        $this->inputs = array_values(array_map(function ($flatSynapse) {
            return new Synapse($flatSynapse['source'], $flatSynapse['weight']);
        }, $flatSynapses));
    }
}
