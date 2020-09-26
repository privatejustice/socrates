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
}
