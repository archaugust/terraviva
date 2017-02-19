<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity
 * @ORM\Table(name="tv1_pagetype")
 */
class PageType
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     */
    private $pageTypeTitle;
    /**
     * @ORM\Column(type="string",length=50)
     * @Assert\NotBlank()
     */
    public $pageType;

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
     * Set pageType
     *
     * @param string $pageType
     *
     * @return PageType
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
     * Set pageTypeTitle
     *
     * @param string $pageTypeTitle
     *
     * @return PageType
     */
    public function setPageTypeTitle($pageTypeTitle)
    {
        $this->pageTypeTitle = $pageTypeTitle;

        return $this;
    }

    /**
     * Get pageTypeTitle
     *
     * @return string
     */
    public function getPageTypeTitle()
    {
        return $this->pageTypeTitle;
    }
}
