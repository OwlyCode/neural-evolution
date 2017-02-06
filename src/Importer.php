<?php

namespace OwlyCode\NeuralEvolution;

class Importer
{
    public function import(array $data)
    {
        $global = [];
        $inputs = [];
        $outputs = [];
        $hidden = [];

        // Build neurons
        foreach($data['inputs'] as $neuronData) {
            $neuron = new InputNeuron($neuronData['name']);
            $inputs[]= $neuron;
            $global[$neuronData['name']]= $neuron;
        }

        foreach($data['hidden'] as $neuronData) {
            $neuron = new Neuron($neuronData['activation'], $neuronData['layer'], $neuronData['name']);
            $hidden[]= $neuron;
            $global[$neuronData['name']]= $neuron;

            foreach ($neuronData['inputs'] as $input) {
                $synapse = new Synapse($global[$input['source']], $input['weight']);
                $neuron->attach($synapse);
            }
        }

        foreach($data['outputs'] as $neuronData) {
            $neuron = new Neuron($neuronData['activation'], $neuronData['layer'], $neuronData['name']);
            $outputs[]= $neuron;
            $global[$neuronData['name']]= $neuron;

            foreach ($neuronData['inputs'] as $input) {
                $synapse = new Synapse($global[$input['source']], $input['weight']);
                $neuron->attach($synapse);
            }
        }

        return new Organism($inputs, $outputs, $hidden);
    }
}