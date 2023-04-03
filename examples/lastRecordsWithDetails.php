<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$parser = new \jkbnerad\Markvartice\Deska\Parser();

$lastRecords = $parser->parseLast();

$lastRecordsWithDetails = [];
foreach($lastRecords->getRecords() as $record) {
    $lastRecordsWithDetails[] = [
        'title' => $record->getTitle(),
        'from' => $record->getFrom(),
        'to' => $record->getTo(),
        'link' => $record->getLink(),
        'detail' => $parser->parseDetailByUrl($record->getLink()),
    ];
}

var_dump($lastRecordsWithDetails);
