<?php

namespace App\DataCollector;


use App\Article\ArticleCatalogue;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class SourcesCollector extends DataCollector
{

    private $catalogue;

    /**
     * SourcesCollector constructor.
     * @param ArticleCatalogue $catalogue
     */
    public function __construct(ArticleCatalogue $catalogue)
    {
        $this->catalogue = $catalogue;
    }

    /**
     * Collects data for the given Request and Response.
     * @param Request $request
     * @param Response $response
     * @param \Exception|null $exception
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = $this->catalogue->getStats();
    }

    /**
     * Returns the name of the collector.
     *
     * @return string The collector name
     */
    public function getName()
    {
        return 'app.sources_collector';
    }

    /**
     * Resets this data collector to its initial state.
     */
    public function reset()
    {
        $this->data = [];
    }

    public function getStats() {
        return $this->data;
    }
}