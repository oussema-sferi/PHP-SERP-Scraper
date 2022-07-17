<?php

use Goutte\Client;
class HelperService
{
    const DOMAIN = 'https://search.ipaustralia.gov.au';
    private array $element = [];
    private array $result;
    public function __construct()
    {
        $this->result = [];
    }
    public function scrapeData($crawler)
    {
        $output = $crawler->filter('#resultsTable > tbody > tr')->each(function ($node) {
            //$this->counter++;
            //$logoUrl = $node->filter('.image > img')->attr('src');
            $index = $node->filter('.table-index')->text();
            //echo $index;

            //
            $number = $node->filter('.number')->text();
            $this->element["number"]= $number;

            //
            if($node->filter('.image > img')->count() > 0)
            {
                $logoUrl = $node->filter('.image > img')->attr('src');
            } else {
                $logoUrl = "none";
            }
            $this->element["logo_url"]= $logoUrl;

            $name = $node->filter('.words')->text();
            $this->element["name"]= $name;

            //
            $classes = $node->filter('.classes')->text();
            $this->element["classes"]= $classes;

            //
            if($node->filter('.status > div > span')->count() > 0)
            {
                $status = trim($node->filter('.status > div > span')->text());
            } else {
                $status = $node->filter('.status')->html();
                //$status = $node->filter('.status')->html();
                $status = trim(preg_replace('~<i(.*?)</i>~Usi', '', $status));
            }
            $colonIndex = strpos($status, ':');
            if($colonIndex !== false)
            {
                $statusOne = substr($status, 0, $colonIndex);
                $statusTwo = trim(substr($status, $colonIndex + 1));
                $this->element["status1"]= $statusOne;
                $this->element["status2"]= $statusTwo;
            } else {
                $this->element["status1"]= $status;
                $this->element["status2"]= $status;
            }

            //
            $detailsPageUrl = $node->attr('data-markurl');
            $this->element["details_page_url"]= self::DOMAIN . $detailsPageUrl;

            //


            // $status = $node->filter('.status > div > span')->text();

            $this->result[$index] = json_encode($this->element, JSON_UNESCAPED_SLASHES);

            //array_push($this->test, $response);
            //var_dump($index);
            // var_dump($this->element["status1"]);
            //var_dump($this->element["status2"]);
        });
        return $this->result;
    }
}