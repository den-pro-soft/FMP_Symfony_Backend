<?php

namespace RestBundle\Services;

use JobApis\Jobs\Client\Queries\IndeedQuery;
use JobApis\Jobs\Client\Providers\IndeedProvider;

/**
 * Class Indeed
 * @package RestBundle\Services
 */
class Indeed
{
    /**
     * @var string $publisher_id
     */
    protected $publisher_id;

    /**
     * Indeed constructor.
     * @param $publisher_id
     */
    public function __construct($publisher_id)
    {
        $this->publisher_id = $publisher_id;
    }

    /**
     * @param $position
     * @param $location
     * @return \JobApis\Jobs\Client\Collection
     */
    public function search($position, $location)
    {
        $query = new IndeedQuery(['publisher' => $this->publisher_id, 'co' => 'us']);

        $query->set('q', $position)->set('l', $location)->set('highlight', '0');

        $client = new IndeedProvider($query);

        return $client->getJobs();
    }
}
