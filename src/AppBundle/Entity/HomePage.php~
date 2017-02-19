<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity
 * @ORM\Table(name="tv1_homepage")
 */
class HomePage
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $main_title;
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $main_subtitle;
    /**
     * @ORM\Column(type="string", length=200)
     */
    private $main_url;
    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\File(mimeTypes={ "image/jpg","image/jpeg","image/png" })
     */
    private $main_img;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $thumb_1_title;
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $thumb_1_subtitle;
    /**
     * @ORM\Column(type="string", length=200)
     */
    private $thumb_1_url;
    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\File(mimeTypes={ "image/jpg","image/jpeg","image/png" })
     */
    private $thumb_1_img;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $thumb_2_title;
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $thumb_2_subtitle;
    /**
     * @ORM\Column(type="string", length=200)
     */
    private $thumb_2_url;
    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\File(mimeTypes={ "image/jpg","image/jpeg","image/png" })
     */
    private $thumb_2_img;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $thumb_3_title;
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $thumb_3_subtitle;
    /**
     * @ORM\Column(type="string", length=200)
     */
    private $thumb_3_url;
    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\File(mimeTypes={ "image/jpg","image/jpeg","image/png" })
     */
    private $thumb_3_img;
    
    /**
     * @ORM\Column(type="text")
     */
    private $content;

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
     * Set mainTitle
     *
     * @param string $mainTitle
     *
     * @return HomePage
     */
    public function setMainTitle($mainTitle)
    {
        $this->main_title = $mainTitle;

        return $this;
    }

    /**
     * Get mainTitle
     *
     * @return string
     */
    public function getMainTitle()
    {
        return $this->main_title;
    }

    /**
     * Set mainSubtitle
     *
     * @param string $mainSubtitle
     *
     * @return HomePage
     */
    public function setMainSubtitle($mainSubtitle)
    {
        $this->main_subtitle = $mainSubtitle;

        return $this;
    }

    /**
     * Get mainSubtitle
     *
     * @return string
     */
    public function getMainSubtitle()
    {
        return $this->main_subtitle;
    }

    /**
     * Set mainUrl
     *
     * @param string $mainUrl
     *
     * @return HomePage
     */
    public function setMainUrl($mainUrl)
    {
        $this->main_url = $mainUrl;

        return $this;
    }

    /**
     * Get mainUrl
     *
     * @return string
     */
    public function getMainUrl()
    {
        return $this->main_url;
    }

    /**
     * Set mainImg
     *
     * @param string $mainImg
     *
     * @return HomePage
     */
    public function setMainImg($mainImg)
    {
        $this->main_img = $mainImg;

        return $this;
    }

    /**
     * Get mainImg
     *
     * @return string
     */
    public function getMainImg()
    {
        return $this->main_img;
    }

    /**
     * Set thumb1Title
     *
     * @param string $thumb1Title
     *
     * @return HomePage
     */
    public function setThumb1Title($thumb1Title)
    {
        $this->thumb_1_title = $thumb1Title;

        return $this;
    }

    /**
     * Get thumb1Title
     *
     * @return string
     */
    public function getThumb1Title()
    {
        return $this->thumb_1_title;
    }

    /**
     * Set thumb1Subtitle
     *
     * @param string $thumb1Subtitle
     *
     * @return HomePage
     */
    public function setThumb1Subtitle($thumb1Subtitle)
    {
        $this->thumb_1_subtitle = $thumb1Subtitle;

        return $this;
    }

    /**
     * Get thumb1Subtitle
     *
     * @return string
     */
    public function getThumb1Subtitle()
    {
        return $this->thumb_1_subtitle;
    }

    /**
     * Set thumb1Url
     *
     * @param string $thumb1Url
     *
     * @return HomePage
     */
    public function setThumb1Url($thumb1Url)
    {
        $this->thumb_1_url = $thumb1Url;

        return $this;
    }

    /**
     * Get thumb1Url
     *
     * @return string
     */
    public function getThumb1Url()
    {
        return $this->thumb_1_url;
    }

    /**
     * Set thumb1Img
     *
     * @param string $thumb1Img
     *
     * @return HomePage
     */
    public function setThumb1Img($thumb1Img)
    {
        $this->thumb_1_img = $thumb1Img;

        return $this;
    }

    /**
     * Get thumb1Img
     *
     * @return string
     */
    public function getThumb1Img()
    {
        return $this->thumb_1_img;
    }

    /**
     * Set thumb2Title
     *
     * @param string $thumb2Title
     *
     * @return HomePage
     */
    public function setThumb2Title($thumb2Title)
    {
        $this->thumb_2_title = $thumb2Title;

        return $this;
    }

    /**
     * Get thumb2Title
     *
     * @return string
     */
    public function getThumb2Title()
    {
        return $this->thumb_2_title;
    }

    /**
     * Set thumb2Subtitle
     *
     * @param string $thumb2Subtitle
     *
     * @return HomePage
     */
    public function setThumb2Subtitle($thumb2Subtitle)
    {
        $this->thumb_2_subtitle = $thumb2Subtitle;

        return $this;
    }

    /**
     * Get thumb2Subtitle
     *
     * @return string
     */
    public function getThumb2Subtitle()
    {
        return $this->thumb_2_subtitle;
    }

    /**
     * Set thumb2Url
     *
     * @param string $thumb2Url
     *
     * @return HomePage
     */
    public function setThumb2Url($thumb2Url)
    {
        $this->thumb_2_url = $thumb2Url;

        return $this;
    }

    /**
     * Get thumb2Url
     *
     * @return string
     */
    public function getThumb2Url()
    {
        return $this->thumb_2_url;
    }

    /**
     * Set thumb2Img
     *
     * @param string $thumb2Img
     *
     * @return HomePage
     */
    public function setThumb2Img($thumb2Img)
    {
        $this->thumb_2_img = $thumb2Img;

        return $this;
    }

    /**
     * Get thumb2Img
     *
     * @return string
     */
    public function getThumb2Img()
    {
        return $this->thumb_2_img;
    }

    /**
     * Set thumb3Title
     *
     * @param string $thumb3Title
     *
     * @return HomePage
     */
    public function setThumb3Title($thumb3Title)
    {
        $this->thumb_3_title = $thumb3Title;

        return $this;
    }

    /**
     * Get thumb3Title
     *
     * @return string
     */
    public function getThumb3Title()
    {
        return $this->thumb_3_title;
    }

    /**
     * Set thumb3Subtitle
     *
     * @param string $thumb3Subtitle
     *
     * @return HomePage
     */
    public function setThumb3Subtitle($thumb3Subtitle)
    {
        $this->thumb_3_subtitle = $thumb3Subtitle;

        return $this;
    }

    /**
     * Get thumb3Subtitle
     *
     * @return string
     */
    public function getThumb3Subtitle()
    {
        return $this->thumb_3_subtitle;
    }

    /**
     * Set thumb3Url
     *
     * @param string $thumb3Url
     *
     * @return HomePage
     */
    public function setThumb3Url($thumb3Url)
    {
        $this->thumb_3_url = $thumb3Url;

        return $this;
    }

    /**
     * Get thumb3Url
     *
     * @return string
     */
    public function getThumb3Url()
    {
        return $this->thumb_3_url;
    }

    /**
     * Set thumb3Img
     *
     * @param string $thumb3Img
     *
     * @return HomePage
     */
    public function setThumb3Img($thumb3Img)
    {
        $this->thumb_3_img = $thumb3Img;

        return $this;
    }

    /**
     * Get thumb3Img
     *
     * @return string
     */
    public function getThumb3Img()
    {
        return $this->thumb_3_img;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return HomePage
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
}
