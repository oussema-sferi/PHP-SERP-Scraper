<?php
class HelperService
{
    const DOMAIN = 'https://search.ipaustralia.gov.au';
    private array $row;
    private array $singlePageResults;
    public function getSinglePageData($crawler) : array
    {
        $output = $crawler->filter('#resultsTable > tbody > tr')->each(function ($node) {
            // Mapping Data
            $index = $node->filter('.table-index')->text();
            //
            $number = $node->filter('.number')->text();
            $this->row["number"]= $number;
            //
            $logoUrl = $node->filter('.image > img')->count() > 0 ? $node->filter('.image > img')->attr('src') : "none";
            $this->row["logo_url"]= $logoUrl;
            $name = $node->filter('.words')->text();
            $this->row["name"]= $name;
            //
            $classes = $node->filter('.classes')->text();
            $this->row["classes"]= $classes;
            //
            if($node->filter('.status > div > span')->count() > 0)
                $status = trim($node->filter('.status > div > span')->text());
            else {
                $status = $node->filter('.status')->html();
                $status = trim(preg_replace('~<i(.*?)</i>~Usi', '', $status));
            }
            $colonIndex = strpos($status, ':');
            if($colonIndex !== false)
            {
                $statusOne = substr($status, 0, $colonIndex);
                $statusTwo = trim(substr($status, $colonIndex + 1));
                $this->row["status1"]= $statusOne;
                $this->row["status2"]= $statusTwo;
            } else {
                $this->row["status1"]= $status;
                $this->row["status2"]= $status;
            }
            //
            $detailsPageUrl = $node->attr('data-markurl');
            $this->row["details_page_url"]= self::DOMAIN . $detailsPageUrl;
            // encoding element to JSON
            $this->singlePageResults[$index] = json_encode($this->row, JSON_UNESCAPED_SLASHES);
        });
        return $this->singlePageResults;
    }

    public function getAllResultsCount($crawler)
    {
        $paginationString = $crawler->filter('.pagination-bottom > .right-aligned > .pagination-count')->text();
        $resultsCount = substr($paginationString, strpos($paginationString, 'of ') + 3);
        echo 'Total results count = ' . $resultsCount . PHP_EOL;
    }

    public function checkIfSinglePage($crawler) : bool
    {
        return $crawler->filter('.pagination-bottom > .right-aligned > .pagination-buttons > a:last-child')->filter('.disabled')->count() > 0;
    }

    public function checkIfNoResults($crawler) : bool
    {
        return $crawler->filter('.no-content')->count() > 0;
    }
}