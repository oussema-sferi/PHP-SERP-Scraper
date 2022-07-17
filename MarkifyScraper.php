<?php
require './Utils/HelperService.php';
use Goutte\Client;
class MarkifyScraper
{
    private array $finalResult;
    private $crawler;
    private $client;
    //private array $element = [];
    //private int $counter = 0;
    const DOMAIN = 'https://search.ipaustralia.gov.au';
    public function getData()
    {
        $keyword = readline('Enter Your search keyword: ');
        //$test->number = "125";
        if(!$keyword) return;
        $helper = new HelperService();
        $this->client = new Client();

        $this->crawler = $this->client->request('GET', self::DOMAIN . "/trademarks/search/advanced");
        // $crawler = $client->click($crawler->selectLink('Sign in')->link());
        $form = $this->crawler->selectButton('Search')->form();
        $this->crawler = $this->client->submit($form, ['wv[0]' => $keyword]);
        // $header_car =  $crawler->filter("resultsTable")->text();
        // $output = $crawler->filter('#resultsTable');

       $this->finalResult = $helper->scrapeData($this->crawler);
        //var_dump(trim($this->crawler->filter('.pagination-bottom > .right-aligned > .pagination-buttons > a')->filter('.disabled')->text()));

        if($this->crawler->filter('.pagination-bottom > .right-aligned > .pagination-buttons > a:last-child')->filter('.disabled')->count() > 0)
        {
            echo "yes";
            var_dump($this->finalResult);
            return;
        }
        /*$nodeCheck = $this->crawler->filter('.pagination-bottom > .right-aligned > .pagination-buttons > a:last-child')->filter('.disabled')->text();
        var_dump($nodeCheck);
        if(trim($this->crawler->filter('.pagination-bottom > .right-aligned > .pagination-buttons > a')->filter('.disabled')->text()) === "Next page")
        {
            echo "yes";
            var_dump($this->finalResult);
            return;
        }*/
        while($this->crawler->filter('.pagination-bottom > .right-aligned > .pagination-buttons > a:last-child')->filter('.disabled')->count() === 0)
        {
            $helper = new HelperService();
            // Click on the "Security Advisories" link
            $link = $this->crawler->selectLink('Next page')->link();
            $this->crawler = $this->client->click($link);

            var_dump($this->crawler->filter('.pagination-bottom > .right-aligned > .pagination-count')->text());
            $this->finalResult += $helper->scrapeData($this->crawler);

        }

        // Click on the "next page" link
        /*$link = $crawler->selectLink('Security Advisories')->link();
        $crawler = $client->click($link);*/
       //echo 'Total search results : ' . $this->counter . PHP_EOL;
      // print_r($this->finalResult );
        //var_dump($this->counter);
        //var_dump($_SERVER['SERVER_NAME']);
        var_dump($this->finalResult);
    }
}