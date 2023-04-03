<?php

declare(strict_types=1);

use jkbnerad\Markvartice\Deska\File;
use jkbnerad\Markvartice\Deska\Parser;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

$parser = new Parser();

$lastItems = $parser->parseLast(file_get_contents(__DIR__ . '/assets/lastItems.html'));
Assert::same(20, $lastItems->getCount());

$records = $lastItems->getRecords();

Assert::same('2023-03-14', $records[0]->getFrom());
Assert::same('2023-03-30', $records[0]->getTo());
Assert::same('ZÁMĚR OBCE - prodej pozemku p.č. 2875/5, katastrální území obce Markvartice', $records[0]->getTitle());
Assert::same('https://markvartice.cz/obcan/536-zamer-obce-prodej-pozemku-p-c-2875-5-katastralni-uzemi-obce-markvartice', $records[0]->getLink());

Assert::null($records[3]->getTo());

$detail = $parser->parseDetail(file_get_contents(__DIR__ . '/assets/detail.html'));

Assert::same('Veřejná vyhláška - Územní rozhodnutí Strejda.net s.r.o.', $detail->getTitle());
Assert::same('', $detail->getDescription());
Assert::equal(
    [
        File::create('verejna-vyhlaska-strejdanet.pdf', 'https://markvartice.cz/obcan/download/740-verejna-vyhlaska-strejdanet-pdf'),
        File::create('verejna-vyhlaska-mapa.pdf', 'https://markvartice.cz/obcan/download/741-verejna-vyhlaska-mapa-pdf')
    ],
    $detail->getFiles()
);

Assert::same('2023-03-30', $detail->getFrom());
Assert::same('2023-04-17', $detail->getTo());
