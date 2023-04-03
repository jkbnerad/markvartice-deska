<?php

declare(strict_types=1);

namespace jkbnerad\Markvartice\Deska;

use Symfony\Component\DomCrawler\Crawler;

class Parser
{
    private const urlList = 'https://markvartice.cz/obcan/uredni-deska/';
    private const detailUrl = 'https://markvartice.cz';

    private const replaceCzDateToIsoPattern = '$3-$2-$1';

    private const czDatePattern = '@(\d{1,2})\.(\d{1,2})\.(\d{4})@';

    public function parseLast(?string $html = null): ListOfRecords
    {
        if ($html === null) {
            $html = file_get_contents(self::urlList);
            if ($html === false) {
                throw new \RuntimeException('Cannot read url');
            }
        }

        $crawler = new Crawler($html);

        $records = [];
        foreach ($crawler->filter('table tr') as $domElement) {
            $tr = new Crawler($domElement);
            $td = $tr->filter('td');
            if ($td->count() === 3) {
                $from = $td->eq(0)->text();
                $to = $td->eq(1)->text();
                $text = $td->eq(2)->text();
                $link = new Crawler($td->getNode(2));
                $link = $link->filter('a')->attr('href');

                if (is_string($link) && preg_match(self::czDatePattern, $from)) {
                    if (!preg_match(self::czDatePattern, $to)) {
                        $to = null;
                    }
                    $from = preg_replace(self::czDatePattern, self::replaceCzDateToIsoPattern, $from);
                    if (is_string($from)) {
                        $to = $to ? preg_replace(self::czDatePattern, self::replaceCzDateToIsoPattern, $to) : null;
                        $records[] = Record::create($from, $to, $text, $this->getAbsoluteUrl($link));
                    }
                }
            }
        }

        return ListOfRecords::create($records);
    }

    public function parseDetail(string $html): Detail
    {
        $crawler = new Crawler($html);
        $detail = [];
        $div = $crawler->filter('div.itemboard')->getNode(0);
        $h1 = (new Crawler($div))->filter('h1')->text();
        $description = (new Crawler($div))->filter('div.description');
        if ($description->count() > 0) {
            $description = $description->text();
        } else {
            $description = '';
        }

        $annotation = (new Crawler($div))->filter('span.titledescription');
        if ($annotation->count() > 0) {
            $annotation = $annotation->text();
        } else {
            $annotation = '';
        }

        $detail['title'] = trim($h1);
        $detail['description'] = trim(str_replace($annotation, '', $description));

        $files = (new Crawler($div))->filter('div.files a');
        $detail['files'] = [];
        if ($files->count() > 0) {
            foreach ($files as $file) {
                $href = $file->attributes?->getNamedItem('href');
                if ($href instanceof \DOMAttr) {
                    $file = File::create($file->textContent, $this->getAbsoluteUrl($href->value));
                    $detail['files'][] = $file;
                }
            }
        }

        $details = (new Crawler($div))->filter('div.details');
        if ($details->count() > 0) {
            $from = $details->filter('p')->eq(0)->text();
            $to = $details->filter('p')->eq(1)->text();

            $from = $this->parseDateFromText($from);
            $to = $this->parseDateFromText($to);
            if ($from) {
                $detail['from'] = $from;
                $detail['to'] = $to;
                return Detail::create($detail['title'], $detail['description'], $detail['from'], $detail['to'], $detail['files']);
            }
        }

        throw new \RuntimeException('Cannot parse detail');
    }

    private function getAbsoluteUrl(string $relativePath): string
    {
        return sprintf('%s%s', self::detailUrl, $relativePath);
    }

    private function parseDateFromText(?string $value): ?string
    {
        if ($value && preg_match(self::czDatePattern, $value, $matches)) {
            $date = preg_replace(self::czDatePattern, self::replaceCzDateToIsoPattern, $matches[0]);
        } else {
            $date = null;
        }

        return $date;
    }
}
