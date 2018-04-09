<?php

namespace RestBundle\Services;

use JobApis\Jobs\Client\Queries\ZiprecruiterQuery;
use JobApis\Jobs\Client\Providers\ZiprecruiterProvider;

/**
 * Class ZipRecruiter
 * @package RestBundle\Services
 */
class ZipRecruiter
{
    /** @var  string $api_key */
    protected $api_key;

    /**
     * ZipRecruiter constructor.
     * @param $api_key
     */
    public function __construct($api_key)
    {
        $this->api_key = $api_key;
    }

    /**
     * @param string $position
     * @param string $location
     * @param int $jobs_per_page
     * @return \JobApis\Jobs\Client\Collection
     */
    public function search($position, $location, $jobs_per_page = 50)
    {
        $query = new ZiprecruiterQuery(['api_key' => $this->api_key]);

        $query->set('search', $position)
            ->set('location', $location)
            ->set('jobs_per_page', $jobs_per_page);

        $client = new ZiprecruiterProvider($query);

        return $client->getJobs();
    }
}
