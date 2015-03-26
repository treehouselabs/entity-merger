<?php

use TreeHouse\IntegrationBundle\Entity\Company;
use TreeHouse\IntegrationBundle\Entity\FunctionTag;
use TreeHouse\IntegrationBundle\Entity\VacancyLocation;
use TreeHouse\IntegrationBundle\Entity\Branch;
use TreeHouse\IntegrationBundle\Entity\Vacancy;
use TreeHouse\EntityMerger\EntityMerger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EntityMergerTest extends WebTestCase
{
    /**
     * @var EntityMerger
     */
    protected $merger;

    protected function setUp()
    {
        parent::setUp();

        $this->merger = static::createClient()->getContainer()->get('tree_house.entity_merger');
    }

    public function testMergeWithManyToOne()
    {
        $branch1 = new Branch();
        $branch1->setTitle('Branch 1');
        $branch1->setKvkSettlingNumber('00000000001');

        $original = new Vacancy();
        $original->setTitle('original');
        $original->setBody('original body');
        $original->setBranch($branch1);

        $update = new Vacancy();
        $update->setTitle('update');
        $update->setBody('update body');

        $expected = new Vacancy();
        $expected->setTitle('update');
        $expected->setBody('update body');
        $expected->setBranch($branch1);

        /** @var Vacancy $new */
        $new = $this->merger->merge($original, $update);

        $this->assertEquals($expected, $new);
    }

    public function testMergeNullWithManyToOne()
    {
        $branch1 = new Branch();
        $branch1->setTitle('Branch 1');
        $branch1->setKvkSettlingNumber('00000000001');

        $original = new Vacancy();
        $original->setTitle('original');
        $original->setBody('original body');
        $original->setBranch($branch1);

        $update = new Vacancy();
        $update->setTitle('update');
        $update->setBody('update body');

        $expected = new Vacancy();
        $expected->setTitle('update');
        $expected->setBody('update body');

        /** @var Vacancy $new */
        $new = $this->merger->merge($original, $update, null, true);

        $this->assertEquals($expected, $new);
    }

    public function testMergeWithOneToMany()
    {
        $original = new Vacancy();
        $original->setBody('body original');


        $location1 = new VacancyLocation();
        $location1->setGetgeoLocationId(502);
        $location1->setLocationType('city');

        $update = new Vacancy();

        $branch1 = new Branch();
        $branch1->setTitle('Branch 1');
        $branch1->setKvkSettlingNumber('0004356564');
        $branch1->addVacancy($update);

        $update->setTitle('update');
        $update->setBody('body update');
        $update->setBranch($branch1);
        $update->addLocation($location1);

        $expected = new Vacancy();
        $expected->setTitle('update');
        $expected->setBody('body update');
        $expected->setBranch($branch1);
        $expected->addLocation($location1);

        /** @var Vacancy $new */
        $new = $this->merger->merge($original, $update);

        $this->assertEquals($expected, $new);
    }

    public function testMergeNullWithOneToMany()
    {
        $original = new Vacancy();
        $original->setBody('body original');

        $location1 = new VacancyLocation();
        $location1->setGetgeoLocationId(502);
        $location1->setLocationType('city');

        $branch1 = new Branch();
        $branch1->setTitle('Branch 1');
        $branch1->setKvkSettlingNumber('0004356564');
        $branch1->addVacancy($original);

        $original->setBranch($branch1);
        $original->addLocation($location1);

        $update = new Vacancy();
        $update->setTitle('update');
        $update->setBody('body update');

        $expected = new Vacancy();
        $expected->setTitle('update');
        $expected->setBody('body update');


        /** @var Vacancy $new */
        $new = $this->merger->merge($original, $update, null, true);

        $this->assertEquals($expected, $new);

    }

    public function testMergeWithManyToMany()
    {
        $original = new Vacancy();
        $original->setBody('original');

        $functionTag1 = new FunctionTag();
        $functionTag1->setTitle('FunctionTag 1');

        $update = new Vacancy();
        $update->setTitle('update');
        $update->setBody('body update');
        $update->addFunctionTag($functionTag1);

        $expected = new Vacancy();
        $expected->setTitle('update');
        $expected->setBody('body update');
        $expected->addFunctionTag($functionTag1);

        /** @var Vacancy $new */
        $new = $this->merger->merge($original, $update);

        $this->assertEquals($expected, $new);
    }

    public function testMergeNullWithManyToMany()
    {
        $original = new Vacancy();
        $original->setBody('original');

        $functionTag1 = new FunctionTag();
        $functionTag1->setTitle('FunctionTag 1');

        $original->addFunctionTag($functionTag1);

        $update = new Vacancy();
        $update->setTitle('update');
        $update->setBody('body update');

        $expected = new Vacancy();
        $expected->setTitle('update');
        $expected->setBody('body update');

        /** @var Vacancy $new */
        $new = $this->merger->merge($original, $update, null, true);

        $this->assertEquals($expected, $new);
    }

    public function testMergeWithOneToOne()
    {
        $branch1 = new Branch();
        $branch1->setTitle('Branch 1');
        $branch1->setKvkSettlingNumber('0004356564');

        $original = new Company();
        $original->setTitle('original');

        $update = new Company();
        $update->setTitle('update');
        $update->addBranch($branch1);
        $update->setHeadBranch($branch1);

        $expected = new Company();
        $expected->setTitle('update');
        $expected->addBranch($branch1);
        $expected->setHeadBranch($branch1);

        /** @var Vacancy $new */
        $new = $this->merger->merge($original, $update);

        $this->assertEquals($expected, $new);
    }

    public function testMergeNullsWithOneToOne()
    {
        $branch1 = new Branch();
        $branch1->setTitle('Branch 1');
        $branch1->setKvkSettlingNumber('0004356564');

        $original = new Company();
        $original->setTitle('original');
        $original->addBranch($branch1);
        $original->setHeadBranch($branch1);

        $update = new Company();
        $update->setTitle('update');

        $expected = new Company();
        $expected->setTitle('update');

        /** @var Vacancy $new */
        $new = $this->merger->merge($original, $update, null, true);

        $this->assertEquals($expected, $new);
    }

    public function testMergeAssociationsDoesNotCauseDuplicates()
    {
        $ft1 = new FunctionTag();
        $ft1->getTitle('title 1');

        $original = new Vacancy();
        $original->addFunctionTag($ft1);

        $update = new Vacancy();
        $update->addFunctionTag($ft1);

        $expected = new Vacancy();
        $expected->addFunctionTag($ft1); // test it still has one function tag, instead of two

        $new = $this->merger->merge($original, $update);

        $this->assertEquals($expected, $new);
    }

    public function testMergeNullValues()
    {
        $original = new Vacancy();
        $original->setTitle('Test');

        $update = new Vacancy();
        $update->setTitle(null);

        $expected = new Vacancy();

        $result = $this->merger->merge($original, $update, null, true);

        $this->assertEquals($expected, $result);
    }

    public function testWithExclusionFilter()
    {
        $original = new Vacancy();
        $original->setTitle('title 1');
        $original->setBody('body 1');

        $update = new Vacancy();
        $update->setTitle('title update');
        $update->setBody('body 2');

        $expected = new Vacancy();
        $expected->setTitle('title update');
        $expected->setBody('body 1');

        $filter = new \TreeHouse\EntityMerger\Serializer\Exclusion\FieldsExclusionStrategy([
            'title'
        ]);

        $result = $this->merger->merge($original, $update, $filter);

        $this->assertEquals($expected, $result);
    }
}
