<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Data;

use Symfony\Component\Config\Definition\Exception\Exception;

class Recipe
{
    /*
     *  @var string $title 
     */
    private $title;
    /*
     *  @var string $href 
     */
    private $href;
    /*
     *  @var string $ingredients 
     */
    private $ingredients;
    /*
     *  @var string $thumbnail 
     */
    private $thumbnail;

    public function __construct(array $recipe)
    {
        try {
            $this->setHref($recipe["href"]);
            $this->setIngredients($recipe["ingredients"]);
            $this->setThumbnail($recipe["thumbnail"]);
            $this->setTitle($recipe["title"]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }
    
    /**
     * @return string
     */    
    public function getHref()
    {
        return $this->href;
    }

    /**
     * @param string $href
     */
    public function setHref(string $href)
    {
        $this->href = $href;
    }

    /**
     * @return string
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }

    /**
     * @param string $ingredients
     */
    public function setIngredients(string $ingredients)
    {
        $this->ingredients = $ingredients;
    }

    /**
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @param string $thumbnail
     */
    public function setThumbnail(string $thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }
}
