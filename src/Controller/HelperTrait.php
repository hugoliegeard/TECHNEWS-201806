<?php

namespace App\Controller;


use Behat\Transliterator\Transliterator;

trait HelperTrait
{
    /**
     * Permet de générer un Slug à partir d'un String
     * @param $text
     * @return mixed|string
     */
    public function slugify($text)
    {
        return Transliterator::transliterate($text);
    }
}