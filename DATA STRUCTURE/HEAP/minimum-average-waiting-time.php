// PHP

<?php

function minimumAverage($customers) {
    // Sort customers by arrival time
    usort($customers, function($a, $b) {
        return $a[0] - $b[0];
    });

    $currentTime = 0; // Tracks the current time
    $waitingTime = 0; // Total waiting time
    $index = 0; // Index for traversing the customers array
    $pq = new SplPriorityQueue(); // Min-Heap to store processing times
    
    // PriorityQueue stores items in descending order, reverse priority to get min-heap behavior
    $pq->setExtractFlags(SplPriorityQueue::EXTR_DATA);

    while ($index < count($customers) || !$pq->isEmpty()) {
        // Add all customers that have arrived by currentTime to the priority queue
        while ($index < count($customers) && $customers[$index][0] <= $currentTime) {
            $pq->insert($customers[$index], -$customers[$index][1]); // Negative processing time for min-heap
            $index++;
        }

        if (!$pq->isEmpty()) {
            // Get the next customer with the shortest processing time
            $nextCustomer = $pq->extract();
            $currentTime += $nextCustomer[1]; // Advance current time by processing time
            $waitingTime += $currentTime - $nextCustomer[0]; // Calculate waiting time
        } else {
            // If no customers are available, move currentTime to the next customer's arrival
            $currentTime = $customers[$index][0];
        }
    }

    // Return the average waiting time
    return floor($waitingTime / count($customers));
}

// Input reading and output
$stdin = fopen("php://stdin", "r");
$n = intval(trim(fgets($stdin)));
$customers = [];

for ($i = 0; $i < $n; $i++) {
    $customers[] = array_map('intval', explode(' ', trim(fgets($stdin))));
}

echo minimumAverage($customers) . "\n";

fclose($stdin);
