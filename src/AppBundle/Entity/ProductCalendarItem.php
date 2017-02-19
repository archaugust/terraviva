<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tv1_product_calendar_item")
 */
class ProductCalendarItem
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
     * @ORM\Column(type="string", length=20)
     */
    private $bg_color;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $filename;

    /**
     * @ORM\Column(type="integer")
     */
    private $cat_id;

    /**
     * @ORM\ManyToOne(targetEntity="ProductCalendar", inversedBy="items")
     * @ORM\JoinColumn(name="cat_id", referencedColumnName="id")
     */
    private $calendar;

    /**
     * @ORM\Column(type="text")
     */
    private $january;

    /**
     * @ORM\Column(type="text")
     */
    private $february;

    /**
     * @ORM\Column(type="text")
     */
    private $march;

    /**
     * @ORM\Column(type="text")
     */
    private $april;

    /**
     * @ORM\Column(type="text")
     */
    private $may;

    /**
     * @ORM\Column(type="text")
     */
    private $june;

    /**
     * @ORM\Column(type="text")
     */
    private $july;

    /**
     * @ORM\Column(type="text")
     */
    private $august;

    /**
     * @ORM\Column(type="text")
     */
    private $september;

    /**
     * @ORM\Column(type="text")
     */
    private $october;

    /**
     * @ORM\Column(type="text")
     */
    private $november;

    /**
     * @ORM\Column(type="text")
     */
    private $december;

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
     * @return ProductCalendarItem
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
     * Set filename
     *
     * @param string $filename
     *
     * @return ProductCalendarItem
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
     * Set catId
     *
     * @param integer $catId
     *
     * @return ProductCalendarItem
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
     * Set january
     *
     * @param string $january
     *
     * @return ProductCalendarItem
     */
    public function setJanuary($january)
    {
        $this->january = $january;

        return $this;
    }

    /**
     * Get january
     *
     * @return string
     */
    public function getJanuary()
    {
        return $this->january;
    }

    /**
     * Set february
     *
     * @param string $february
     *
     * @return ProductCalendarItem
     */
    public function setFebruary($february)
    {
        $this->february = $february;

        return $this;
    }

    /**
     * Get february
     *
     * @return string
     */
    public function getFebruary()
    {
        return $this->february;
    }

    /**
     * Set march
     *
     * @param string $march
     *
     * @return ProductCalendarItem
     */
    public function setMarch($march)
    {
        $this->march = $march;

        return $this;
    }

    /**
     * Get march
     *
     * @return string
     */
    public function getMarch()
    {
        return $this->march;
    }

    /**
     * Set april
     *
     * @param string $april
     *
     * @return ProductCalendarItem
     */
    public function setApril($april)
    {
        $this->april = $april;

        return $this;
    }

    /**
     * Get april
     *
     * @return string
     */
    public function getApril()
    {
        return $this->april;
    }

    /**
     * Set may
     *
     * @param string $may
     *
     * @return ProductCalendarItem
     */
    public function setMay($may)
    {
        $this->may = $may;

        return $this;
    }

    /**
     * Get may
     *
     * @return string
     */
    public function getMay()
    {
        return $this->may;
    }

    /**
     * Set june
     *
     * @param string $june
     *
     * @return ProductCalendarItem
     */
    public function setJune($june)
    {
        $this->june = $june;

        return $this;
    }

    /**
     * Get june
     *
     * @return string
     */
    public function getJune()
    {
        return $this->june;
    }

    /**
     * Set july
     *
     * @param string $july
     *
     * @return ProductCalendarItem
     */
    public function setJuly($july)
    {
        $this->july = $july;

        return $this;
    }

    /**
     * Get july
     *
     * @return string
     */
    public function getJuly()
    {
        return $this->july;
    }

    /**
     * Set august
     *
     * @param string $august
     *
     * @return ProductCalendarItem
     */
    public function setAugust($august)
    {
        $this->august = $august;

        return $this;
    }

    /**
     * Get august
     *
     * @return string
     */
    public function getAugust()
    {
        return $this->august;
    }

    /**
     * Set september
     *
     * @param string $september
     *
     * @return ProductCalendarItem
     */
    public function setSeptember($september)
    {
        $this->september = $september;

        return $this;
    }

    /**
     * Get september
     *
     * @return string
     */
    public function getSeptember()
    {
        return $this->september;
    }

    /**
     * Set october
     *
     * @param string $october
     *
     * @return ProductCalendarItem
     */
    public function setOctober($october)
    {
        $this->october = $october;

        return $this;
    }

    /**
     * Get october
     *
     * @return string
     */
    public function getOctober()
    {
        return $this->october;
    }

    /**
     * Set november
     *
     * @param string $november
     *
     * @return ProductCalendarItem
     */
    public function setNovember($november)
    {
        $this->november = $november;

        return $this;
    }

    /**
     * Get november
     *
     * @return string
     */
    public function getNovember()
    {
        return $this->november;
    }

    /**
     * Set december
     *
     * @param string $december
     *
     * @return ProductCalendarItem
     */
    public function setDecember($december)
    {
        $this->december = $december;

        return $this;
    }

    /**
     * Get december
     *
     * @return string
     */
    public function getDecember()
    {
        return $this->december;
    }

    /**
     * Set calendar
     *
     * @param \AppBundle\Entity\ProductCalendar $calendar
     *
     * @return ProductCalendarItem
     */
    public function setCalendar(\AppBundle\Entity\ProductCalendar $calendar = null)
    {
        $this->calendar = $calendar;

        return $this;
    }

    /**
     * Get calendar
     *
     * @return \AppBundle\Entity\ProductCalendar
     */
    public function getCalendar()
    {
        return $this->calendar;
    }

    /**
     * Set bgColor
     *
     * @param string $bgColor
     *
     * @return ProductCalendarItem
     */
    public function setBgColor($bgColor)
    {
        $this->bg_color = $bgColor;

        return $this;
    }

    /**
     * Get bgColor
     *
     * @return string
     */
    public function getBgColor()
    {
        return $this->bg_color;
    }
}
