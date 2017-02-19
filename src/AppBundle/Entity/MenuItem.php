<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tv1_menu_item")
 */
class MenuItem
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="integer")
     */
    public $menuId;
    /**
     * @ORM\Column(type="integer")
     */
    public $parentId;
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $alias;
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $pageType;
    /**
     * @ORM\Column(type="text")
     */
    private $pageTypeId;
    /**
     * @ORM\Column(type="integer")
     */
    private $sortOrder;

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
     * Set menuId
     *
     * @param integer $menuId
     *
     * @return MenuItem
     */
    public function setMenuId($menuId)
    {
        $this->menuId = $menuId;

        return $this;
    }

    /**
     * Get menuId
     *
     * @return integer
     */
    public function getMenuId()
    {
        return $this->menuId;
    }

    /**
     * Set parentId
     *
     * @param integer $parentId
     *
     * @return MenuItem
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     *
     * @return integer
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return MenuItem
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
     * @return MenuItem
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
     * Set pageType
     *
     * @param string $pageType
     *
     * @return MenuItem
     */
    public function setPageType($pageType)
    {
        $this->pageType = $pageType;

        return $this;
    }

    /**
     * Get pageType
     *
     * @return string
     */
    public function getPageType()
    {
        return $this->pageType;
    }

    /**
     * Set pageTypeId
     *
     * @param string $pageTypeId
     *
     * @return MenuItem
     */
    public function setPageTypeId($pageTypeId)
    {
        $this->pageTypeId = $pageTypeId;

        return $this;
    }

    /**
     * Get pageTypeId
     *
     * @return string
     */
    public function getPageTypeId()
    {
        return $this->pageTypeId;
    }

    /**
     * Set sortOrder
     *
     * @param integer $sortOrder
     *
     * @return MenuItem
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * Get sortOrder
     *
     * @return integer
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }
}
