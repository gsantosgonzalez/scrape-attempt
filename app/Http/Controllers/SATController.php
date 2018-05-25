<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Blacktrue\Scraping\SATScraper;
use Blacktrue\Scraping\URLS;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Client;
use Blacktrue\Scraping\Headers;

class SATController extends Controller
{
    protected $client;
    protected $scraper;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function login(Request $request)
    {
        $this->scraper = new SATScraper([
            'rfc' => $request->rfc,
            'ciec' => $request->pass,
            'captcha' => $request->captcha,
            'tipoDescarga' => 'emitidos',
            'cancelados' => false
        ]);

        $this->scraper->downloadPeriod(2018, 4, 1, 2018, 4, 30);
        $response = $this->scraper->getData();

        return view('home')->with('response', $response);
    }

    public function loadLogin(Request $request)
    {
        $response = [];

        $loginData = shell_exec("/phantomjs/phantomjs/bin/phantomjs /shared/satScrapper.local/www/public/js/scrape/getSessionVariables.js");

        $exploded = explode(';', $loginData);

        foreach ($exploded as $fragment) {
            if ($fragment != '') {
                $data = explode('=', $fragment);

                if ($data[0] == 'imgpath') {
                    $response['imgpath'] = $data[1];
                } else {
                    $response['cookies'][$data[0]] = $data[1];
                }
            }
        }

        return view('sat.login')->with([
            'imgpath' => $response['imgpath'],
            'cookies' => $response['cookies']
        ]);
    }
}