<?php
require 'vendor/autoload.php';
require_once 'src/Services/HelperService.php';
require_once 'src/Controller/MarkifyScraper.php';
use App\Controller\MarkifyScraper;
$scraper = new MarkifyScraper();
$scraper->getData();
