<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="tv1_contact")
 */
class Contact
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
     * @ORM\Column(type="string", length=50)
     */
    private $lname = '';
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $email;
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $phone;
    /**
     * @ORM\Column(type="text")
     */
    private $message;
    /**
     * @ORM\Column(type="integer")
     */
    private $date_submitted;

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
     * @return Contact
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
     * Set lname
     *
     * @param string $lname
     *
     * @return Contact
     */
    public function setLname($lname)
    {
        $this->lname = $lname;

        return $this;
    }

    /**
     * Get lname
     *
     * @return string
     */
    public function getLname()
    {
        return $this->lname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Contact
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
     * Set phone
     *
     * @param string $phone
     *
     * @return Contact
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Contact
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set dateSubmitted
     *
     * @param integer $dateSubmitted
     *
     * @return Contact
     */
    public function setDateSubmitted($dateSubmitted)
    {
        $this->date_submitted = $dateSubmitted;

        return $this;
    }

    /**
     * Get dateSubmitted
     *
     * @return integer
     */
    public function getDateSubmitted()
    {
        return $this->date_submitted;
    }
}
