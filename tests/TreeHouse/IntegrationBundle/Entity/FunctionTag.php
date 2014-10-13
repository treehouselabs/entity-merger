<?php

namespace TreeHouse\IntegrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * For example: PHP, Java, etc.
 *
 * @ORM\Entity()
 * @ORM\Table()
 */
class FunctionTag
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Serializer\Groups("publish_vacancy")
     */
    protected $title;

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Get the value of id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of title.
     *
     * @param string $title
     *
     * @return FunctionTag
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    public function __toString()
    {
        return $this->title;
    }
}
