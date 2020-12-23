<?php

namespace App\Entity;

class AnnonceSearch
{

    private $maxPrice;

    private $categorie;

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
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set the value of categorie
     *
     * @return  self
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

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
