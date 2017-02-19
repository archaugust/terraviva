<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="tv1_product_item")
 */
class ProductItem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $cat_id;

    /**
     * @ORM\ManyToOne(targetEntity="ProductCategory", inversedBy="items")
     * @ORM\JoinColumn(name="cat_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subtitle;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $alias;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $code;

    /**
     * @ORM\Column(type="text")
     */
    private $description_short;
    
    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $price_original;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $price_sale;

    /**
     * @ORM\Column(type="integer")
     */
    private $in_stock;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $related_tags;
    
    /**
     * @ORM\Column(type="simple_array")
     */
    private $filters;

    /**
     * @ORM\Column(type="smallint")
     */
    private $pending;

    /**
     * @ORM\Column(type="integer")
     */
    private $date_added;

    /**
     * @ORM\Column(type="integer")
     */
    private $date_modified;

    /**
     * @ORM\Column(type="boolean")
     */
    private $featured = FALSE;

    /**
     * @ORM\Column(type="boolean")
     */
    private $publish = FALSE;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted = FALSE;

    /**
     * @ORM\Column(type="integer")
     */
    private $hits;

    /**
     * @ORM\OneToMany(targetEntity="ProductItemImage", mappedBy="item")
     */

    private $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set catId
     *
     * @param integer $catId
     *
     * @return ProductItem
     */
    public function setCatId($catId)
    {
        $this->cat_id = $catId;

        return $this;
    }

    /**
     * Get catId
     *
     * @return integer
     */
    public function getCatId()
    {
        return $this->cat_id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return ProductItem
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set subtitle
     *
     * @param string $subtitle
     *
     * @return ProductItem
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set alias
     *
     * @param string $alias
     *
     * @return ProductItem
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return ProductItem
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return ProductItem
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set priceOriginal
     *
     * @param string $priceOriginal
     *
     * @return ProductItem
     */
    public function setPriceOriginal($priceOriginal)
    {
        $this->price_original = $priceOriginal;

        return $this;
    }

    /**
     * Get priceOriginal
     *
     * @return string
     */
    public function getPriceOriginal()
    {
        return $this->price_original;
    }

    /**
     * Set priceSale
     *
     * @param string $priceSale
     *
     * @return ProductItem
     */
    public function setPriceSale($priceSale)
    {
        $this->price_sale = $priceSale;

        return $this;
    }

    /**
     * Get priceSale
     *
     * @return string
     */
    public function getPriceSale()
    {
        return $this->price_sale;
    }

    /**
     * Set inStock
     *
     * @param integer $inStock
     *
     * @return ProductItem
     */
    public function setInStock($inStock)
    {
        $this->in_stock = $inStock;

        return $this;
    }

    /**
     * Get inStock
     *
     * @return integer
     */
    public function getInStock()
    {
        return $this->in_stock;
    }

    /**
     * Set filters
     *
     * @param array $filters
     *
     * @return ProductItem
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * Get filters
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Set pending
     *
     * @param integer $pending
     *
     * @return ProductItem
     */
    public function setPending($pending)
    {
        $this->pending = $pending;

        return $this;
    }

    /**
     * Get pending
     *
     * @return integer
     */
    public function getPending()
    {
        return $this->pending;
    }

    /**
     * Set dateAdded
     *
     * @param integer $dateAdded
     *
     * @return ProductItem
     */
    public function setDateAdded($dateAdded)
    {
        $this->date_added = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return integer
     */
    public function getDateAdded()
    {
        return $this->date_added;
    }

    /**
     * Set dateModified
     *
     * @param integer $dateModified
     *
     * @return ProductItem
     */
    public function setDateModified($dateModified)
    {
        $this->date_modified = $dateModified;

        return $this;
    }

    /**
     * Get dateModified
     *
     * @return integer
     */
    public function getDateModified()
    {
        return $this->date_modified;
    }

    /**
     * Set featured
     *
     * @param boolean $featured
     *
     * @return ProductItem
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;

        return $this;
    }

    /**
     * Get featured
     *
     * @return boolean
     */
    public function getFeatured()
    {
        return $this->featured;
    }

    /**
     * Set publish
     *
     * @param boolean $publish
     *
     * @return ProductItem
     */
    public function setPublish($publish)
    {
        $this->publish = $publish;

        return $this;
    }

    /**
     * Get publish
     *
     * @return boolean
     */
    public function getPublish()
    {
        return $this->publish;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\ProductCategory $category
     *
     * @return ProductItem
     */
    public function setCategory(\AppBundle\Entity\ProductCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\ProductCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add image
     *
     * @param \AppBundle\Entity\ProductItemImage $image
     *
     * @return ProductItem
     */
    public function addImage(\AppBundle\Entity\ProductItemImage $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param \AppBundle\Entity\ProductItemImage $image
     */
    public function removeImage(\AppBundle\Entity\ProductItemImage $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set hits
     *
     * @param integer $hits
     *
     * @return ProductItem
     */
    public function setHits($hits)
    {
        $this->hits = $hits;

        return $this;
    }

    /**
     * Get hits
     *
     * @return integer
     */
    public function getHits()
    {
        return $this->hits;
    }

    /**
     * Set descriptionShort
     *
     * @param string $descriptionShort
     *
     * @return ProductItem
     */
    public function setDescriptionShort($descriptionShort)
    {
        $this->description_short = $descriptionShort;

        return $this;
    }

    /**
     * Get descriptionShort
     *
     * @return string
     */
    public function getDescriptionShort()
    {
        return $this->description_short;
    }

    /**
     * Set relatedTags
     *
     * @param string $relatedTags
     *
     * @return ProductItem
     */
    public function setRelatedTags($relatedTags)
    {
        $this->related_tags = $relatedTags;

        return $this;
    }

    /**
     * Get relatedTags
     *
     * @return string
     */
    public function getRelatedTags()
    {
        return $this->related_tags;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return ProductItem
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean
     */
    public function getDeleted()
    {
        return $this->deleted;
    }
}
