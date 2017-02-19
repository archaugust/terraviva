<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tv1_product_item_image")
 */
class ProductItemImage
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
    private $item_id;

    /**
     * @ORM\ManyToOne(targetEntity="ProductItem", inversedBy="images")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     */
    private $item;

    /**
     * @ORM\Column(type="text")
     */
    private $filename;

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
     * Set itemId
     *
     * @param integer $itemId
     *
     * @return ProductItemImage
     */
    public function setItemId($itemId)
    {
        $this->item_id = $itemId;

        return $this;
    }

    /**
     * Get itemId
     *
     * @return integer
     */
    public function getItemId()
    {
        return $this->item_id;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return ProductItemImage
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set item
     *
     * @param \AppBundle\Entity\ProductItem $item
     *
     * @return ProductItemImage
     */
    public function setItem(\AppBundle\Entity\ProductItem $item = null)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return \AppBundle\Entity\ProductItem
     */
    public function getItem()
    {
        return $this->item;
    }
}
