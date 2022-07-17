<?php
/*require 'Calculator.php';
use MyLoveToKnowApp\Calculator;*/
// Instantiation of Calculator class and calling its sum method.
//$calculator = new Calculator("D:/test/A.txt");
//$calc->sum("A.txt");
//$calculator->filesNumbersAddition();
//$keyword = readline('Enter Your search keyword: ');
/*$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://search.ipaustralia.gov.au/trademarks/search/advanced');
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, 'test');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$result= curl_exec($curl);
curl_close($curl);*/
// For output
/*echo $keyword;*/
/*var_dump($result);*/
require 'vendor/autoload.php';
require 'MarkifyScraper.php';
$scraper = new MarkifyScraper();
$scraper->getData();
