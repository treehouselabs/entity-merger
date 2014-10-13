<?php

namespace TreeHouse\IntegrationBundle\Entity;;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Company
 *
 * @ORM\Entity()
 * @ORM\Table()
 */
class Company
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
    protected $kvkNumber;

    /**
     * @var Branch[]|Collection The branches of a Company
     *
     * @ORM\OneToMany(targetEntity="Branch", mappedBy="company", cascade={"persist","remove"})
     * @Serializer\Groups("publish_vacancy")
     */
    protected $branches;

    /**
     * @var Branch
     *
     * @ORM\OneToOne(targetEntity="Branch")
     * @Serializer\Groups("publish_vacancy")
     */
    protected $headBranch;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->branches = new ArrayCollection();
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
     * @return Company
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
     * Set kvkNumber
     *
     * @param string $kvkNumber
     *
     * @return Company
     */
    public function setKvkNumber($kvkNumber)
    {
        $this->kvkNumber = $kvkNumber;

        return $this;
    }

    /**
     * Get kvkNumber
     *
     * @return string
     */
    public function getKvkNumber()
    {
        return $this->kvkNumber;
    }

    /**
     * @return Branch[]|Collection
     */
    public function getBranches()
    {
        return $this->branches;
    }

    /**
     * @param Branch[]|Collection $branches
     */
    public function setBranches($branches)
    {
        $this->branches = $branches;
    }

    /**
     * @param Branch $branch
     */
    public function addBranch(Branch $branch)
    {
        $this->branches->add($branch);
    }

    /**
     * @param Branch $branch
     *
     * @return bool
     */
    public function hasBranch(Branch $branch)
    {
        return $this->branches->contains($branch);
    }

    /**
     * @param Branch $branch
     */
    public function removeBranch(Branch $branch)
    {
        $this->branches->removeElement($branch);
    }

    /**
     * @return Branch
     */
    public function getHeadBranch()
    {
        return $this->headBranch;
    }

    /**
     * @param Branch $headBranch
     */
    public function setHeadBranch($headBranch)
    {
        if (!$this->hasBranch($headBranch)) {
            throw new \InvalidArgumentException('Company does not have branch, add it first');
        }

        $this->headBranch = $headBranch;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }
}
