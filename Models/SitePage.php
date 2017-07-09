<?php

use GuzzleHttp\Cookie\CookieJar;
use PHPHtmlParser\Dom;

class SitePage
{
    public static function createFromCsvLine($csvLine)
    {
        $sitePage = new static();
        $sitePage->url = isset($csvLine[0]) ? $csvLine[0] : null;
        $sitePage->metaTitle = isset($csvLine[1]) ? $csvLine[1] : null;
        $sitePage->metaDescription = isset($csvLine[2]) ? $csvLine[2] : null;

        return $sitePage;
    }

    public static function createFromHtmlResponse($url)
    {
        $response = self::sendRequest($url);
        $html = (string) $response->getBody();

        $sitePage = new static();
        $sitePage->url = $url;
        $sitePage->metaDescription = $sitePage->getMetaTagContent($html, 'description');
        $sitePage->metaTitle = $sitePage->getMetaTagContent($html, 'title');

        return $sitePage;
    }

    private static function sendRequest($url)
    {
        $client = new GuzzleHttp\Client();

        $cookieJar = CookieJar::fromArray([
            'Tests' => 'seo'
        ], parse_url($url, PHP_URL_HOST));

        return $client->request('GET', $url, ['cookies' => $cookieJar]);
    }

    private function getMetaTagContent($html, $metaTagName)
    {
        $dom = new Dom;
        $dom->load($html);
        $metaTags = $dom->find(sprintf('meta[name="%s"]', $metaTagName));
        if (count($metaTags) > 1) {
            throw new \Exception(sprintf('На странице по адресу более одного элемнта <meta name="%s">', $this->getUrl(), $metaTagName));
        }

        return isset($metaTags[0]) ? $metaTags[0]->getAttribute('content') : null;
    }

    private $metaTitle;
    private $metaDescription;
    private $url;

    /**
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string|null
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * @return string|null
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }
}
