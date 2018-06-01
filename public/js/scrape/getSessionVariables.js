var webpage = require('webpage');
var fs = require('fs');

const url = 'https://portalcfdi.facturaelectronica.sat.gob.mx/';

var d = new Date();
var page = webpage.create();
var captchaPath = 'img/captcha/' + d.getTime() + '.jpeg';

page.customHeaders = {
    "Referer": "https://cfdiau.sat.gob.mx/nidp/wsfed_redir_cont_portalcfdi.jsp?wa=wsignin1.0&wtrealm=https%3a%2f%2fcfdicontribuyentes.accesscontrol.windows.net%2f&wreply=https%3a%2f%2fcfdicontribuyentes.accesscontrol.windows.net%2fv2%2fwsfederation&wctx=cHI9d3NmZWRlcmF0aW9uJnJtPWh0dHBzJTNhJTJmJTJmcG9ydGFsY2ZkaS5mYWN0dXJhZWxlY3Ryb25pY2Euc2F0LmdvYi5teCZyeT1odHRwcyUzYSUyZiUyZnBvcnRhbGNmZGkuZmFjdHVyYWVsZWN0cm9uaWNhLnNhdC5nb2IubXglMmYmY3g9cm0lM2QwJTI2aWQlM2RwYXNzaXZlJTI2cnUlM2QlMjUyZg2",
    "User-Agent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.13; rv:60.0) Gecko/20100101 Firefox/60.0",
    "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
    "Accept-Language": "es-MX,es;q=0.8,en-US;q=0.5,en;q=0.3",
    "Connection": "keep-alive",
    "Upgrade-Insecure-Requests": "1",
    "Pragma": "no-cache",
    "Cache-Control": "no-cache",
    "Content-Length": "0"
};

page.open(url, function(status) {

    if (status === 'success') {
        page.render('/shared/satScrapper.local/www/public/js/scrape/home.jpeg', {format:'jpeg', quality:'100'});

        action = page.evaluate(function () {
            return document.forms[0].action;
            document.forms[0].submit();
        });

        page.onLoadFinished = function () {
            page.clipRect = {
                top: 285,
                left: 12,
                width: 200,
                height: 70
            }
            page.render('/shared/satScrapper.local/www/public/' + captchaPath, {format:'jpeg', quality:'100'});

            console.log('imgpath:' + captchaPath + ';');
            console.log('action:' + action + ';');

            var cookies = page.cookies;

            for(var i in cookies) {
                console.log(cookies[i].name + ':' + cookies[i].value + ';');
            }

            phantom.exit();
        };
    }
});