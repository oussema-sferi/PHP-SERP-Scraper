<?php
require './Utils/HelperService.php';
use Goutte\Client;
class MarkifyScraper
{
    const DOMAIN = 'https://search.ipaustralia.gov.au';
    public function getData()
    {
        $keyword = readline('Enter Your search keyword: ');
        if(!$keyword) return;
        $helper = new HelperService();
        $client = new Client();
        $crawler = $client->request('GET', self::DOMAIN . "/trademarks/search/advanced");
        $form = $crawler->selectButton('Search')->form();
        $crawler = $client->submit($form, ['wv[0]' => $keyword]);
        // If No Results Found
        if($helper->checkIfNoResults($crawler))
        {
            echo 'No results found!';
            return;
        }
        $allResults = $helper->getSinglePageData($crawler);
        // Search Results Count
        $helper->getAllResultsCount($crawler);
        // If results pages are in a single page
        if($helper->checkIfSinglePage($crawler))
        {
            print_r($allResults);
            return;
        }
        // If Results Pages Are In More Than One Page
        while(!$helper->checkIfSinglePage($crawler))
        {
            // Automate Clicking On Next Page Button
            $link = $crawler->selectLink('Next page')->link();
            $crawler = $client->click($link);
            //Appending Next Page Results To The Existing results Array
            $allResults += $helper->getSinglePageData($crawler);
        }
        print_r($allResults);
    }
}