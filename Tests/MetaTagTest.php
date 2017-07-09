<?php

include 'models/SitePage.php';

class MetaTagTest extends \PHPUnit\Framework\TestCase
{
    public function seoDataProvider()
    {
        $data = [];
        foreach (array_map('str_getcsv', file('data.csv')) as $csvLine) {
            $data[] = [SitePage::createFromCsvLine($csvLine), SitePage::createFromHtmlResponse($csvLine[0])];
        }

        return $data;
    }

    /**
     * @dataProvider seoDataProvider
     * @param SitePage $csvSitePage
     * @param SitePage $htmlSitePage
     */
    public function testMetaTitle(SitePage $csvSitePage, SitePage $htmlSitePage)
    {
        $this->assertEquals($csvSitePage->getMetaTitle(), $htmlSitePage->getMetaTitle(),
            sprintf('На странице %s неверное значение атрибута content в теге <meta name="description">. Ожидали "%s", а получили "%s',
                $csvSitePage->getUrl(),
                $csvSitePage->getMetaDescription(),
                $htmlSitePage->getMetaDescription()
            )
        );
    }

    /**
     * @dataProvider seoDataProvider
     * @param SitePage $csvSitePage
     * @param SitePage $htmlSitePage
     */
    public function testMetaDescription(SitePage $csvSitePage, SitePage $htmlSitePage)
    {
        $this->assertEquals($csvSitePage->getMetaDescription(), $htmlSitePage->getMetaDescription(),
            sprintf('На странице %s неверное значение атрибута content в теге <meta name="title">. Ожидали "%s", а получили "%s',
                $csvSitePage->getUrl(),
                $csvSitePage->getMetaDescription(),
                $htmlSitePage->getMetaDescription()
            )
        );
    }
}
