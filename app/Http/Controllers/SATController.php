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
    protected $cookies;

    public function __construct()
    {
        $this->client = new Client();
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

    public function login(Request $request)
    {
        $loginURL = '';
        $response = $this->client->post('', [
            'future' => true,
            'verify' => false,
            'headers' => [
                'Accept' => ' text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Encoding' => 'gzip, deflate',
                'Accept-Language' => 'en-US,en;q=0.5',
                'Connection' => 'keep-alive',
                'Host' => $host,
                'Referer' => $referer,
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:31.0) Gecko/20100101 Firefox/31.0',
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'form_params' => [
                'Ecom_Password' => $request->pass,
                'Ecom_User_ID' => $request->rfc,
                'jcaptcha' => $request->captcha,
                'option' => 'credential',
                'submit' => 'Enviar',
            ],
        ])->getBody()->getContents();

        return view('home')->with('response', $response);
    }
}