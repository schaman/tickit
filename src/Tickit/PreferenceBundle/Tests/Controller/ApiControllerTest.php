<?php

namespace Tickit\PreferenceBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tickit\CoreBundle\Tests\AbstractUnitTest;
use Tickit\PreferenceBundle\Controller\ApiController;
use Tickit\PreferenceBundle\Entity\Preference;

/**
 * ApiController tests
 *
 * @package Tickit\PreferenceBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiControllerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $filterCollectionBuilder;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $preferenceRepo;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $baseHelper;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->filterCollectionBuilder = $this->getMockFilterCollectionBuilder();

        $this->preferenceRepo = $this->getMockBuilder('\Tickit\PreferenceBundle\Entity\Repository\PreferenceRepository')
                                     ->disableOriginalConstructor()
                                     ->getMock();

        $this->baseHelper = $this->getMockBaseHelper();
    }
    
    /**
     * Tests the listAction() method
     */
    public function testListActionBuildsCorrectResponse()
    {
        $preference1 = new Preference();
        $preference1->setName('preference 1');

        $preference2 = new Preference();
        $preference2->setName('preference 2');

        $preferences = [$preference1, $preference2];

        $filters = $this->getMockBuilder('\Tickit\CoreBundle\Filters\Collection\FilterCollection')
                        ->disableOriginalConstructor()
                        ->getMock();

        $request = new Request();
        $this->baseHelper->expects($this->once())
                         ->method('getRequest')
                         ->will($this->returnValue($request));

        $this->filterCollectionBuilder->expects($this->once())
                                      ->method('buildFromRequest')
                                      ->with($request)
                                      ->will($this->returnValue($filters));

        $this->preferenceRepo->expects($this->once())
                             ->method('findByFilters')
                             ->with($filters)
                             ->will($this->returnValue($preferences));

        $decorator = $this->getMockObjectDecorator();
        $decorator->expects($this->exactly(2))
                  ->method('decorate')
                  ->will($this->returnValue(array('preference data')));

        $decorator->expects($this->at(0))
                  ->method('decorate')
                  ->with($preference1, array('id', 'name', 'systemName', 'type'));

        $decorator->expects($this->at(1))
                  ->method('decorate')
                  ->with($preference2, array('id', 'name', 'systemName', 'type'));

        $this->baseHelper->expects($this->once())
                         ->method('getObjectDecorator')
                         ->will($this->returnValue($decorator));

        $expectedData = array(
            array('preference data'),
            array('preference data')
        );
        $response = $this->getController()->listAction();

        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Gets a new controller instance
     *
     * @return ApiController
     */
    private function getController()
    {
        return new ApiController($this->filterCollectionBuilder, $this->preferenceRepo, $this->baseHelper);
    }
}
