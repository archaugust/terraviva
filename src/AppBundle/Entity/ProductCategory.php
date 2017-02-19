<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="tv1_product_category")
 */
class ProductCategory
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
     * @ORM\Column(type="string", length=100)
     */
    private $alias;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $parent_id;

    /**
     * @ORM\ManyToOne(targetEntity="ProductCategory", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $hits;

    /**
     * @ORM\Column(type="integer")
     */
    private $ordering;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $image;

    /**
     * @ORM\Column(type="boolean")
     */
    private $publish;

    /**
     * @ORM\OneToMany(targetEntity="ProductCategory", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\OneToMany(targetEntity="ProductFilter", mappedBy="category")
     */
    private $filters;

    /**
     * @ORM\OneToOne(targetEntity="ProductPlantFinder", mappedBy="category")
     */
    private $plant_finder;
    
    /**
     * @ORM\OneToOne(targetEntity="ProductCategoryInfo", mappedBy="category")
     */
    private $category_info;
    
    /**
     * @ORM\OneToMany(targetEntity="ProductItem", mappedBy="category", fetch="EXTRA_LAZY")
     */
    private $items;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->filters = new ArrayCollection();
        $this->items = new ArrayCollection();
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
     * Set title
     *
     * @param string $title
     *
     * @return ProductCategory
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
     * Set alias
     *
     * @param string $alias
     *
     * @return ProductCategory
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
     * Set description
     *
     * @param string $description
     *
     * @return ProductCategory
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
     * Set parentId
     *
     * @param integer $parentId
     *
     * @return ProductCategory
     */
    public function setParentId($parentId)
    {
        $this->parent_id = $parentId;

        return $this;
    }

    /**
     * Get parentId
     *
     * @return integer
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * Set hits
     *
     * @param integer $hits
     *
     * @return ProductCategory
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
     * Set ordering
     *
     * @param integer $ordering
     *
     * @return ProductCategory
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;

        return $this;
    }

    /**
     * Get ordering
     *
     * @return integer
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return ProductCategory
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set publish
     *
     * @param boolean $publish
     *
     * @return ProductCategory
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
     * Set parent
     *
     * @param \AppBundle\Entity\ProductCategory $parent
     *
     * @return ProductCategory
     */
    public function setParent(\AppBundle\Entity\ProductCategory $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\ProductCategory
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child
     *
     * @param \AppBundle\Entity\ProductCategory $child
     *
     * @return ProductCategory
     */
    public function addChild(\AppBundle\Entity\ProductCategory $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \AppBundle\Entity\ProductCategory $child
     */
    public function removeChild(\AppBundle\Entity\ProductCategory $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Add filter
     *
     * @param \AppBundle\Entity\ProductFilter $filter
     *
     * @return ProductCategory
     */
    public function addFilter(\AppBundle\Entity\ProductFilter $filter)
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * Remove filter
     *
     * @param \AppBundle\Entity\ProductFilter $filter
     */
    public function removeFilter(\AppBundle\Entity\ProductFilter $filter)
    {
        $this->filters->removeElement($filter);
    }

    /**
     * Get filters
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Add item
     *
     * @param \AppBundle\Entity\ProductItem $item
     *
     * @return ProductCategory
     */
    public function addItem(\AppBundle\Entity\ProductItem $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \AppBundle\Entity\ProductItem $item
     */
    public function removeItem(\AppBundle\Entity\ProductItem $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set plantFinder
     *
     * @param \AppBundle\Entity\ProductPlantFinder $plantFinder
     *
     * @return ProductCategory
     */
    public function setPlantFinder(\AppBundle\Entity\ProductPlantFinder $plantFinder = null)
    {
        $this->plant_finder = $plantFinder;

        return $this;
    }

    /**
     * Get plantFinder
     *
     * @return \AppBundle\Entity\ProductPlantFinder
     */
    public function getPlantFinder()
    {
        return $this->plant_finder;
    }

    /**
     * Set categoryInfo
     *
     * @param \AppBundle\Entity\ProductCategoryInfo $categoryInfo
     *
     * @return ProductCategory
     */
    public function setCategoryInfo(\AppBundle\Entity\ProductCategoryInfo $categoryInfo = null)
    {
        $this->category_info = $categoryInfo;

        return $this;
    }

    /**
     * Get categoryInfo
     *
     * @return \AppBundle\Entity\ProductCategoryInfo
     */
    public function getCategoryInfo()
    {
        return $this->category_info;
    }
}
