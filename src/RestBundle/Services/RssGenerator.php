<?php

namespace RestBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use GuzzleHttp\Client;
use SitemapPHP\Sitemap;

/**
 * Class RssGenerator
 * @package RestBundle\Services
 */
class RssGenerator
{
    /**
     * @var string $path
     */
    protected $path;

    /**
     * @var ObjectManager $em
     */
    protected $em;

    /**
     * RssGenerator constructor.
     * @param ObjectManager $em
     * @param $path
     */
    public function __construct(ObjectManager $em, $path)
    {
        $this->em = $em;
        $this->path = $path;
    }

    /**
     * @return bool
     */
    public function generate()
    {
        return true;
        $domain = 'https://findmyprofession.com/generate-rss';
        $client = new Client();
        $res = $client->request('POST', $domain);

        if ($res->getStatusCode() === 200) {
            return true;
        }

        return false;
    }
}
