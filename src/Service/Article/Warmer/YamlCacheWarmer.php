<?php

namespace App\Service\Article\Warmer;


use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmer;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class YamlCacheWarmer extends CacheWarmer
{

    public function isOptional()
    {
        return false;
    }

    /**
     * Warms up the cache.
     *
     * @param string $cacheDir The cache directory
     */
    public function warmUp($cacheDir)
    {
        try {
            $articles = Yaml::parseFile(__DIR__ . '/../articles.yaml');
            $this->writeCacheFile($cacheDir.'/yaml-article.php', serialize($articles));

        } catch (ParseException $exception) {
            printf('Unable to parse the YAML string: %s', $exception->getMessage());
        }
    }
}