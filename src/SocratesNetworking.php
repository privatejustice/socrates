<?php

namespace Socrates;

use GuzzleHttp\Client;
use Socrates\Traits\Actions\ManagesBrokenLinks;
use Socrates\Traits\Actions\ManagesCertificateHealth;
use Socrates\Traits\Actions\ManagesChecks;
use Socrates\Traits\Actions\ManagesDowntime;
use Socrates\Traits\Actions\ManagesMaintenancePeriods;
use Socrates\Traits\Actions\ManagesMixedContent;
use Socrates\Traits\Actions\ManagesSites;
use Socrates\Traits\Actions\ManagesStatusPages;
use Socrates\Traits\Actions\ManagesUptime;
use Socrates\Traits\Actions\ManagesUsers;

class SocratesNetworking
{
    use MakesHttpRequests,
        ManagesSites,
        ManagesChecks,
        ManagesUsers,
        ManagesBrokenLinks,
        ManagesMaintenancePeriods,
        ManagesMixedContent,
        ManagesUptime,
        ManagesDowntime,
        ManagesCertificateHealth,
        ManagesStatusPages;

    /** @var string */
    public $apiToken;

    /** @var \GuzzleHttp\Client */
    public $client;

    public function __construct(string $apiToken, Client $client = null)
    {
        $this->apiToken = $apiToken;

        $this->client = $client ?: new Client([
            'base_uri' => 'https://socrates.app/api/',
            'http_errors' => false,
            'headers' => [
                'Authorization' => 'Bearer '.$this->apiToken,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    protected function transformCollection(array $collection, string $class): array
    {
        return array_map(function ($attributes) use ($class) {
            return new $class($attributes, $this);
        }, $collection);
    }
}
