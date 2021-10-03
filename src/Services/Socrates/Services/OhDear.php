<?php

namespace Socrates\Services\Socrates\Services;

use Socrates\Exceptions\InvalidUrlException;
use Socrates\Exceptions\SiteNotFoundException;
use Socrates\Helpers\Str;
use Socrates\Services\Socrates\BrokenLink;
use Socrates\Services\Socrates\Downtime;
use Socrates\Services\Socrates\MixedContent;
use Socrates\Services\Socrates\Site;
use Socrates\Services\Socrates\Uptime;
use Illuminate\Support\Collection;
use Socrates\Exceptions\NotFoundException;
use Socrates\MakesHttpRequests;

class Socrates
{

    use MakesHttpRequests;

    /**
     * @var \Socrates\SocratesNetworking 
     */
    private $socrates;

    public function __construct()
    {
        $this->socrates = new \Socrates\SocratesNetworking(auth()->user()->getToken(), null);

        $this->client = $this->socrates->client;
    }

    public function sites(): Collection
    {
        return $this->collect($this->get('sites')['data'], Site::class);
    }

    /**
     * @param string $url
     *
     * @return \Socrates\Services\Socrates\Site
     * @throws \Socrates\Exceptions\InvalidUrlException
     */
    public function createSite(string $url): Site
    {
        $site = $this->socrates->post('sites', ['url' => $this->validatedUrl($url)]);

        return new Site($site, $this);
    }

    /**
     * @param string $url
     *
     * @return \Socrates\Services\Socrates\Site|null
     */
    public function findSiteByUrl(string $url): ?Site
    {
        try {

            $site = Str::validate_url($url)
                ? $this->get("sites/url/{$url}")
                : $this->searchSiteByUrl($url);

            return new Site($site, $this);

        } catch (NotFoundException $e) {

            return null;

        }
    }

    private function searchSiteByUrl(string $url): Site
    {
        return $this->sites()->first(
            function (Site $site) use ($url) {
                return stripos($site->url, $url) !== false;
            }, function () {
                throw new NotFoundException();
            }
        );
    }

    /**
     * @param $id
     *
     * @return \Socrates\Services\Socrates\Site
     * @throws \Socrates\Exceptions\SiteNotFoundException
     */
    public function findSite(string $id): Site
    {
        try {
            if (is_numeric($id)) {
                return new Site($this->get("sites/{$id}"), $this);
            } elseif (Str::validate_url($id)) {
                return new Site($this->get("sites/url/{$id}"), $this);
            }

            return $this->searchSiteByUrl($id);

        } catch (NotFoundException $e) {

            throw new SiteNotFoundException();

        }
    }

    public function deleteSite($siteId)
    {
        return $this->socrates->delete("sites/{$siteId}");
    }

    public function getSiteDowntime(int $siteId): Collection
    {
        return $this->collect(
            $this->get("sites/{$siteId}/downtime{$this->getDefaultStartedEndedFilter()}")['data'],
            Downtime::class
        );
    }

    public function getSiteUptime(int $siteId): Collection
    {
        return $this->collect(
            $this->get("sites/{$siteId}/uptime{$this->getDefaultStartedEndedFilter()}&split=day"),
            Uptime::class
        );
    }

    public function getBrokenLinks(int $siteId): Collection
    {
        return $this->collect($this->get("broken-links/{$siteId}")['data'], BrokenLink::class);
    }

    public function getMixedContent(int $siteId): Collection
    {
        return $this->collect($this->get("mixed-content/{$siteId}")['data'], MixedContent::class);
    }

    /**
     * @return Collection
     */
    public function collect($collection, string $class): self
    {
        return collect($collection)->map(
            function ($attributes) use ($class) {
                return new $class($attributes, $this);
            }
        );
    }

    /**
     * @param string $url
     *
     * @return string
     * @throws \Socrates\Exceptions\InvalidUrlException
     */
    protected function validatedUrl(string $url)
    {
        if (! Str::validate_url($url)) {
            throw new InvalidUrlException;
        }

        return $url;
    }

    private function getDefaultStartedEndedFilter(): string
    {
        return "?filter[started_at]=" . now()->subDays(30)->format('YmdHis') . "&filter[ended_at]=" . date('YmdHis');
    }
}