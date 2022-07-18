<?php
namespace App\Controller;
use App\Services\HelperService;
use Goutte\Client;
class MarkifyScraper
{
    public function getData()
    {
        $keyword = readline('Enter Your search keyword: ');
        // If No Search Keyword Provided
        if(!$keyword) return;
        //
        $helper = new HelperService();
        $client = new Client();
        $crawler = $client->request('GET', HelperService::DOMAIN . "/trademarks/search/advanced");
        $form = $crawler->selectButton('Search')->form();
        $crawler = $client->submit($form, ['wv[0]' => $keyword]);
        // If No Results Found
        if($helper->checkIfNoResults($crawler))
        {
            echo 'No results found!';
            return;
        }
        // If 1 Only Result Found --> (Because If We Have Just Only One Search Result, We Will Be Redirected To The Details Page Url Of This Result Automatically Once Submitted)
        if($helper->checkIfOnlyOneResult($crawler))
        {
            echo 'One only result found!'. PHP_EOL;
            $oneResult = $helper->getSingleResultData($crawler);
            print_r($oneResult);
            return;
        }
        // Get First Page Results
        $allResults = $helper->getSinglePageData($crawler);
        // All Search Results Count
        $helper->getAllResultsCount($crawler);
        // If Results Pages Are In A Single Page
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