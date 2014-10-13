<?php

namespace TreeHouse\IntegrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * VacancyLocation
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class VacancyLocation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups("publish_vacancy")
     */
    protected $locationType;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @Serializer\Groups("publish_vacancy")
     */
    protected $getgeoLocationId;

    /**
     * @var Vacancy
     *
     * @ORM\ManyToOne(targetEntity="Vacancy", inversedBy="locations", cascade={"persist"})
     */
    protected $vacancy;

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
     * Set locationType
     *
     * @param string $locationType
     *
     * @return VacancyLocation
     */
    public function setLocationType($locationType)
    {
        $this->locationType = $locationType;

        return $this;
    }

    /**
     * Get locationType
     *
     * @return string
     */
    public function getLocationType()
    {
        return $this->locationType;
    }

    /**
     * Set getgeoLocationId
     *
     * @param integer $getgeoLocationId
     *
     * @return VacancyLocation
     */
    public function setGetgeoLocationId($getgeoLocationId)
    {
        $this->getgeoLocationId = $getgeoLocationId;

        return $this;
    }

    /**
     * Get getgeoLocationId
     *
     * @return integer
     */
    public function getGetgeoLocationId()
    {
        return $this->getgeoLocationId;
    }

    /**
     * @param Vacancy $vacancy
     *
     * @return VacancyLocation
     */
    public function setVacancy(Vacancy $vacancy)
    {
        $this->vacancy = $vacancy;

        return $this;
    }

    /**
     * @return Vacancy
     */
    public function getVacancy()
    {
        return $this->vacancy;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getGetgeoLocationId();
    }
}
