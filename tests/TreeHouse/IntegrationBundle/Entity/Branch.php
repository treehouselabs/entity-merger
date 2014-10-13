<?php

namespace TreeHouse\IntegrationBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Branch (branch/establishment/settlement) of a Company
 *
 * @ORM\Entity()
 * @ORM\Table()
 */
class Branch
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
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
     * @var string
     *
     * @todo unique=true
     * @ORM\Column(type="string", length=12, nullable=true)
     * @Serializer\Groups("publish_vacancy")
     */
    protected $kvkSettlingNumber;

    /**
     * @var Company
     *
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="branches")
     */
    protected $company;

    /**
     * @var Vacancy[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Vacancy", mappedBy="branch")
     */
    protected $vacancies;

    /**
     * @var Vacancy[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Vacancy", mappedBy="providerBranch")
     */
    protected $providedVacancies;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->vacancies         = new ArrayCollection();
        $this->providedVacancies = new ArrayCollection();
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
     * @return Branch
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

    /**
     * Add Vacancy entity to collection (one to many).
     *
     * @param Vacancy $vacancy
     *
     * @return Branch
     */
    public function addVacancy(Vacancy $vacancy)
    {
        $this->vacancies[] = $vacancy;

        return $this;
    }

    /**
     * Get Vacancy entity collection (one to many).
     *
     * @return Collection|Vacancy[]
     */
    public function getVacancies()
    {
        return $this->vacancies;
    }

    /**
     * Remove vacancies
     *
     * @param Vacancy $vacancies
     */
    public function removeVacancy(Vacancy $vacancies)
    {
        $this->vacancies->removeElement($vacancies);
    }

    /**
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param Company $company
     */
    public function setCompany(Company $company)
    {
        $this->company = $company;
    }

    /**
     * @return Collection|Vacancy[]
     */
    public function getProvidedVacancies()
    {
        return $this->providedVacancies;
    }

    /**
     * @param Collection|Vacancy[] $providedVacancies
     */
    public function setProvidedVacancies($providedVacancies)
    {
        $this->providedVacancies = $providedVacancies;
    }

    /**
     * @param Vacancy $vacancy
     */
    public function addProvidedVacancy(Vacancy $vacancy)
    {
        $this->providedVacancies->add($vacancy);
    }

    /**
     * @param Vacancy $vacancy
     */
    public function removeProvidedVacancy(Vacancy $vacancy)
    {
        $this->providedVacancies->removeElement($vacancy);
    }

    /**
     * @return string
     */
    public function getKvkSettlingNumber()
    {
        return $this->kvkSettlingNumber;
    }

    /**
     * @param string $kvkSettlingNumber
     *
     * @return $this
     */
    public function setKvkSettlingNumber($kvkSettlingNumber)
    {
        $this->kvkSettlingNumber = $kvkSettlingNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }
}
