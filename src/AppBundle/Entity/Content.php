<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity
 * @ORM\Table(name="tv1_content")
 */
class Content
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cat_id;
	/**
     * @ORM\ManyToOne(targetEntity="ContentCategory")
     * @ORM\JoinColumn(name="cat_id", referencedColumnName="id")
     */
    private $category;
    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank()
     */
    private $title;
    /**
     * @ORM\Column(type="string", length=200)
     */
    private $alias;
    /**
     * @ORM\Column(type="string", length=200)
     */
    private $image;
    /**
     * @ORM\Column(type="text")
     */
    private $content;
    /**
     * @ORM\Column(type="integer")
     */
    private $hits;
    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTime")
     */
    private $date_added;
    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTime")
     */
    private $date_modified;

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
     * @return Content
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
     * @return Content
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
     * @return Content
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
     * Set image
     *
     * @param string $image
     *
     * @return Content
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
     * Set content
     *
     * @param string $content
     *
     * @return Content
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set hits
     *
     * @param integer $hits
     *
     * @return Content
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
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     *
     * @return Content
     */
    public function setDateAdded($dateAdded)
    {
        $this->date_added = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->date_added;
    }

    /**
     * Set dateModified
     *
     * @param \DateTime $dateModified
     *
     * @return Content
     */
    public function setDateModified($dateModified)
    {
        $this->date_modified = $dateModified;

        return $this;
    }

    /**
     * Get dateModified
     *
     * @return \DateTime
     */
    public function getDateModified()
    {
        return $this->date_modified;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\ContentCategory $category
     *
     * @return Content
     */
    public function setCategory(\AppBundle\Entity\ContentCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\ContentCategory
     */
    public function getCategory()
    {
        return $this->category;
    }
}
