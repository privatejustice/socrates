<?php

namespace Socrates\Resources;

class Site extends ApiResource
{
    /**
     * The id of the site.
     *
     * @var int
     */
    public $id;

    /**
     * The url of the site.
     *
     * @var string
     */
    public $url;

    /**
     * The checks of a site.
     *
     * @var Check[]
     */
    public $checks;

    /**
     * The sort url of the site.
     *
     * @var string
     */
    public $sortUrl;

    public function __construct(array $attributes, $socrates = null)
    {
        parent::__construct($attributes, $socrates);

        $this->checks = array_map(
            function (array $checkAttributes) use ($socrates) {
                return new Check($checkAttributes, $socrates);
            }, $this->checks
        );
    }

    /**
     * Delete the given site.
     *
     * @return void
     */
    public function delete()
    {
        $this->socrates->deleteSite($this->id);
    }

    public function startMaintenance(int $stopMaintenanceAfterSeconds = 60 * 60)
    {
        $this->socrates->startSiteMaintenance($this->id, $stopMaintenanceAfterSeconds);
    }

    public function stopMaintenance()
    {
        $this->socrates->stopSiteMaintenance($this->id);
    }

    /**
     * Get the broken links for this site.
     *
     * @return array
     */
    public function brokenLinks()
    {
        return $this->socrates->brokenLinks($this->id);
    }

    /**
     * Get the detected mixed content for a site.
     *
     * @return array
     */
    public function mixedContent()
    {
        return $this->socrates->mixedContent($this->id);
    }

    /**
     * Get the uptime percentages for a site.
     *
     * @param string $startedAt Must be in format Ymdhis
     * @param string $endedAt   Must be in format Ymdhis
     * @param string $split     Use hour, day or month
     *
     * @return array
     */
    public function uptime(string $startedAt, string $endedAt, string $split)
    {
        return $this->socrates->uptime($this->id, $startedAt, $endedAt, $split);
    }

    /**
     * Get the downtime periods for a site.
     *
     * @param string $startedAt Must be in format Ymdhis
     * @param string $endedAt   Must be in format Ymdhis
     *
     * @return array
     */
    public function downtime(string $startedAt, string $endedAt)
    {
        return $this->socrates->downtime($this->id, $startedAt, $endedAt);
    }

    /**
     * Get information on the certificate of the site.
     *
     * @return array
     */
    public function certificateHealth()
    {
        return $this->socrates->certificateHealth($this->id);
    }
}
