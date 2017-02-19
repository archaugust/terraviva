<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tv1_product_filter")
 */
class ProductFilter
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $tags;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return ProductFilter
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
     * Set tags
     *
     * @param string $tags
     *
     * @return ProductFilter
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set catId
     *
     * @param integer $catId
     *
     * @return ProductFilter
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
     * Set category
     *
     * @param \AppBundle\Entity\ProductCategory $category
     *
     * @return ProductFilter
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
}
