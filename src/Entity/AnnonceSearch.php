<?php

namespace App\Entity;

class AnnonceSearch
{

    private $maxPrice;

    private $category;

    private $ville;

    /**
     * Get the value of maxPrice
     */
    public function getMaxPrice()

    {

        return $this->maxPrice;
    }

    /**
     * Set the value of maxPrice
     *
     * @return  self
     */
    public function setMaxPrice($maxPrice)

    {
        $this->maxPrice = $maxPrice;

        return $this;
    }

    /**
     * Get the value of categorie
     */
    public function getCategory()
    {

        return $this->category;
    }

    /**
     * Set the value of categorie
     *
     * @return  self
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the value of ville
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set the value of ville
     *
     * @return  self
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }
}
