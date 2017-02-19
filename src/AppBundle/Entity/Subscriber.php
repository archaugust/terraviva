<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="tv1_subscriber")
 */
class Subscriber
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $fname;
    /**
     * @ORM\Column(type="string", length=60)
     */
    private $email;
	/**
	 * @ORM\Column(type="integer")
	 */
    private $date_subscribed;
    
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
     * Set fname
     *
     * @param string $fname
     *
     * @return Subscriber
     */
    public function setFname($fname)
    {
        $this->fname = $fname;

        return $this;
    }

    /**
     * Get fname
     *
     * @return string
     */
    public function getFname()
    {
        return $this->fname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Subscriber
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set dateSubscribed
     *
     * @param integer $dateSubscribed
     *
     * @return Subscriber
     */
    public function setDateSubscribed($dateSubscribed)
    {
        $this->date_subscribed = $dateSubscribed;

        return $this;
    }

    /**
     * Get dateSubscribed
     *
     * @return integer
     */
    public function getDateSubscribed()
    {
        return $this->date_subscribed;
    }
}
