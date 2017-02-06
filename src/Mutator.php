<?php

namespace OwlyCode\NeuralEvolution;

class Mutator
{
    public static function generateNeuron($layer)
    {
        return new Neuron("sigmoid", $layer);
    }

    public static function mutate(Organism $organism)
    {
        switch (floor(6*Rand::getFloat())) {
            case 0:
                return self::addNeuron($organism);
            case 1:
                return self::addSynapse($organism);
            case 2:
                return self::removeNeuron($organism);
            case 3:
                return self::changeWeight($organism);
            case 4:
                self::removeSynapse($organism);
        }
    }

    public static function addNeuron(Organism $organism)
    {
        $layer = Rand::getFloat();

        $source = $organism->getRandomNeuron('previous', $layer);
        $target = $organism->getRandomNeuron('next', $layer);

        $neuron = self::generateNeuron($layer);
        $target->attach(new Synapse($neuron, Rand::getFloatNegative()));
        $neuron->attach(new Synapse($source, Rand::getFloatNegative()));
        $organism->insertNeuron($neuron);
    }

    public static function addSynapse(Organism $organism)
    {
        $layer = Rand::getFloat();

        $source = $organism->getRandomNeuron('previous', $layer);
        $target = $organism->getRandomNeuron('next', $layer);

        $target->attach(new Synapse($source, 10*Rand::getFloatNegative()));
    }

    public static function removeNeuron(Organism $organism)
    {
        $neuron = $organism->getRandomHiddenNeuron();

        if ($neuron) {
            $organism->removeNeuron($neuron);
        }
    }

    public static function changeWeight(Organism $organism)
    {
        $neuron = $organism->getRandomNeuron();

        if ($neuron) {
            $synapse = $neuron->getRandomInput();
            if ($synapse) {
                $synapse->setWeight(10*Rand::getFloatNegative());
            }
        }
    }

    public static function removeSynapse(Organism $organism)
    {
        $neuron = $organism->getRandomNeuron();

        if ($neuron) {
            $synapse = $neuron->getRandomInput();
            if ($synapse) {
                $neuron->purgeSynapses($synapse->getSource());

                if (!$neuron->hasInputs()) {
                    $organism->removeNeuron($neuron);
                }
            }
        }
    }
}
