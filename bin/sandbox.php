<?php

include 'vendor/autoload.php';

use OwlyCode\NeuralEvolution\Contest;
use OwlyCode\NeuralEvolution\InputNeuron;
use OwlyCode\NeuralEvolution\Neuron;
use OwlyCode\NeuralEvolution\Organism;
use OwlyCode\NeuralEvolution\Specy;
use OwlyCode\NeuralEvolution\Trial\AndTrial;
use OwlyCode\NeuralEvolution\Trial\OrTrial;
use OwlyCode\NeuralEvolution\Trial\XorTrial;

$organism = new Organism(
    [new InputNeuron(), new InputNeuron()],
    [new Neuron("sigmoid")]
);

$contest = new Contest([$organism], new AndTrial(), 10);

$contest->onEachIteration(function (array $organisms, $generation) {
    echo sprintf("Generation %d... Current highest scores : \n", $generation, $organisms[0]->getFitness());

    foreach ($organisms as $organism) {
        echo sprintf("  - %d neurons : %s\n", count($organism->getAllNeurons()), $organism->getFitness());
    }

    echo "\n";
});

$winner = $contest->evolve(3, 3000);

echo "\n\nWe got a winner !\n";
echo "1, 1 => " . $winner->stimulate([1, 1])[0] . "\n";
echo "1, 0 => " . $winner->stimulate([1, 0])[0] . "\n";
echo "0, 1 => " . $winner->stimulate([0, 1])[0] . "\n";
echo "0, 0 => " . $winner->stimulate([0, 0])[0] . "\n\n";

echo "Winner genome : " . base64_encode(json_encode($winner->export())) . "\n\n";
echo "Preview it with bin/gen2graph <genome> <name>";

$current = $winner;

while ($current = $current->getAncestor()) {
    echo base64_encode(json_encode($current->export())) . "\n\n";
}
