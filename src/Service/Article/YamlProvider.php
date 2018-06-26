<?php

namespace App\Service\Article;


use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class YamlProvider
{
    /**
     * Récupère les articles au format YAML
     * et retourne un Tableau.
     * @return iterable
     */
    public function getArticles(): iterable
    {
        try {
            return Yaml::parseFile(__DIR__ . '/articles.yaml')['data'];
        } catch (ParseException $exception) {
            printf('Unable to parse the YAML string: %s', $exception->getMessage());
        }
    }
}