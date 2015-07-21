<?php

namespace TreeHouse\IntegrationBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity()
 * @ORM\Table()
 */
class Vacancy
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\ReadOnly
     * @Serializer\Groups("publish_vacancy")
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
     * full text of the vacancy
     *
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     * @Serializer\Groups("publish_vacancy")
     */
    protected $body;

    /**
     * Excluded vacancy
     *
     * @var string
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Serializer\Exclude
     */
    protected $excluded;

    /**
     * @var Branch The branch of a Company
     *
     * @ORM\ManyToOne(targetEntity="Branch", inversedBy="vacancies")
     * @Serializer\Groups("publish_vacancy")
     */
    protected $branch;

    /**
     * @var Branch The branch of a Company
     *
     * @ORM\ManyToOne(targetEntity="Branch", inversedBy="providedVacancies")
     * @Serializer\Groups("publish_vacancy")
     */
    protected $providerBranch;

    /**
     * @var Collection|VacancyLocation[]
     *
     * @ORM\OneToMany(targetEntity="VacancyLocation", mappedBy="vacancy", orphanRemoval=true, cascade={"persist", "remove"})
     * @Serializer\Groups("publish_vacancy")
     */
    protected $locations;

    /**
     * @var Collection|FunctionTag[]
     *
     * @ORM\ManyToMany(targetEntity="FunctionTag")
     * @Serializer\Groups("publish_vacancy")
     */
    protected $functionTags;

    /**
     * datetime the vacancy is published/found/opened
     * Indicates when the applicant can start on the job
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"publish_vacancy", "api_vacancy_details"})
     */
    protected $datetimeAvailable;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->locations    = new ArrayCollection();
        $this->functionTags = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->title;
    }

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
     * @return Vacancy
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
     * Set body
     *
     * @param string $body
     *
     * @return Vacancy
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getExcluded()
    {
        return $this->excluded;
    }

    /**
     * @param string $excluded
     *
     * @return Vacancy
     */
    public function setExcluded($excluded)
    {
        $this->excluded = $excluded;

        return $this;
    }

    /**
     * Add locations
     *
     * @param VacancyLocation $locations
     *
     * @return Vacancy
     */
    public function addLocation(VacancyLocation $locations)
    {
        $this->locations[] = $locations;

        return $this;
    }

    /**
     * Remove locations
     *
     * @param VacancyLocation $locations
     */
    public function removeLocation(VacancyLocation $locations)
    {
        $this->locations->removeElement($locations);
    }

    /**
     * Get locations
     *
     * @return Collection|VacancyLocation[]
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * @param FunctionTag $functionTag
     */
    public function addFunctionTag(FunctionTag $functionTag)
    {
        $this->functionTags->add($functionTag);
    }

    /**
     * @param FunctionTag $functionTag
     */
    public function removeFunctionTag(FunctionTag $functionTag)
    {
        $this->functionTags->removeElement($functionTag);
    }

    /**
     * @return FunctionTag
     */
    public function getFunctionTags()
    {
        return $this->functionTags;
    }

    /**
     * @param FunctionTag $functionTags
     */
    public function setFunctionTags($functionTags)
    {
        $this->functionTags = $functionTags;
    }

    /**
     * @return Branch
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * @param Branch $branch
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;
    }

    /**
     * @return Branch
     */
    public function getProviderBranch()
    {
        return $this->providerBranch;
    }

    /**
     * @param Branch $providerBranch
     */
    public function setProviderBranch($providerBranch)
    {
        $this->providerBranch = $providerBranch;
    }

    /**
     * @return \DateTime
     */
    public function getDatetimeAvailable()
    {
        return $this->datetimeAvailable;
    }

    /**
     * @param \DateTime $datetimeAvailable
     *
     * @return $this
     */
    public function setDatetimeAvailable($datetimeAvailable)
    {
        $this->datetimeAvailable = $datetimeAvailable;

        return $this;
    }
}
