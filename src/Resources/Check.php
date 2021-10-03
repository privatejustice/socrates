<?php

namespace Socrates\Resources;

class Check extends ApiResource
{
    /**
     * The id of the site.
     *
     * @var int
     */
    public $id;

    /**
     * The type of the check.
     *
     * @var string
     */
    public $type;

    /**
     * The human readable version of type.
     *
     * @var string
     */
    public $label;

    /**
     * Is the check enabled?
     *
     * @var bool
     */
    public $enabled;

    /**
     * Enable the check.
     *
     * @return void
     */
    public function enable(): void
    {
        $updatedCheck = $this->socrates->enableCheck($this->id);

        $this->enabled = $updatedCheck->enabled;
    }

    /**
     * Disable the check.
     *
     * @return void
     */
    public function disable(): void
    {
        $updatedCheck = $this->socrates->disableCheck($this->id);

        $this->enabled = $updatedCheck->enabled;
    }

    /**
     * Request a new run.
     *
     * @return void
     */
    public function requestRun()
    {
        $this->socrates->requestRun($this->id);
    }

    /**
     * Snooze this check.
     *
     * @return void
     */
    public function snooze(int $minutes)
    {
        $this->socrates->snooze($this->id, $minutes);
    }

    /**
     * Unsnooze this check.
     *
     * @return void
     */
    public function unsnooze()
    {
        $this->socrates->unsnooze($this->id);
    }
}
