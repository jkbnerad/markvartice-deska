# markvartice-deska

Parsing the official board of the municipality of Markvartice.

List of items: https://markvartice.cz/obcan/uredni-deska/actual

Detail (example): https://markvartice.cz/obcan/539-verejna-vyhlaska-uzemni-rozhodnuti-strejda-net-s-r-o

## Example

```php

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

```
