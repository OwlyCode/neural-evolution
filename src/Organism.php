<?php

namespace OwlyCode\NeuralEvolution;

class Organism
{
    private $inputs;

    private $outputs;

    private $hidden;

    public function __construct(array $inputs, array $outputs, array $hidden = [])
    {
        $this->inputs = $inputs;
        $this->outputs = $outputs;
        $this->hidden = $hidden;
    }

    public function optimize()
    {
        foreach ($this->hidden as $neuron) {
            $neuron->optimize();
        }

        foreach ($this->outputs as $neuron) {
            $neuron->optimize();
        }
    }

    public function export()
    {
        return [
            'inputs' => array_map(function ($neuron) {
                return $neuron->export();
            }, $this->inputs),
            'hidden' => array_map(function ($neuron) {
                return $neuron->export();
            }, $this->hidden),
            'outputs' => array_map(function ($neuron) {
                return $neuron->export();
            }, $this->outputs),
        ];
    }

    public function reset()
    {
        array_map(function ($neuron) {
            return $neuron->reset();
        }, $this->inputs);
        array_map(function ($neuron) {
            return $neuron->reset();
        }, $this->hidden);
        array_map(function ($neuron) {
            return $neuron->reset();
        }, $this->outputs);
    }

    public function clone()
    {
        $clone = unserialize(serialize($this));

        return $clone;
    }

    public function getHiddenNeurons()
    {
        return $this->hidden;
    }

    public function insertNeuron(Neuron $neuron)
    {
        $this->hidden[] = $neuron;

        usort($this->hidden, function (Neuron $a, Neuron $b) {
            return $a->getLayer() <=> $b->getLayer();
        });
    }

    public function removeNeuron(Neuron $neuron)
    {
        $this->hidden = array_values(array_filter($this->hidden, function ($n) use ($neuron) {
            $n->purgeSynapses($neuron);

            return $n !== $neuron;
        }));

        foreach ($this->outputs as $output) {
            $output->purgeSynapses($neuron);
        }
    }

    private function getAllNeurons()
    {
        return array_merge($this->inputs, $this->outputs, $this->hidden);
    }

    private function getPreviousNeurons($layer)
    {
        return array_filter($this->getAllNeurons(), function ($neuron) use ($layer) {
            return $neuron->getLayer() < $layer;
        });
    }

    private function getNextNeurons($layer)
    {
        return array_filter($this->getAllNeurons(), function ($neuron) use ($layer) {
            return $neuron->getLayer() > $layer;
        });
    }

    public function getRandomNeuron($method = 'all', $args = null)
    {
        $neurons = call_user_func([$this, sprintf('get%sNeurons', ucfirst($method))], $args);
        shuffle($neurons);

        return $neurons[0];
    }


    public function getRandomHiddenNeuron()
    {
        $neurons = $this->getHiddenNeurons();
        shuffle($neurons);

        return $neurons[0] ?? null;
    }

    public function stimulate(array $values)
    {
        array_map(function (InputNeuron $input, $value) {
            $input->setState($value);
        }, $this->inputs, $values);

        foreach ($this->hidden as $neuron) {
            $neuron->stimulate();
        }

        return array_map(function (Neuron $neuron) {
            $neuron->stimulate();

            return $neuron->getState();
        }, $this->outputs);
    }
}