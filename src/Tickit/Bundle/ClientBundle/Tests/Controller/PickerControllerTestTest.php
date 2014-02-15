<?php

namespace Tickit\Bundle\ClientBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Tickit\Bundle\ClientBundle\Controller\PickerController;
use Tickit\Component\Filter\Collection\FilterCollection;
use Tickit\Component\Filter\Map\Client\ClientFilterMapper;
use Tickit\Component\Test\AbstractUnitTest;

/**
 * PickerController tests
 *
 * @package Tickit\Bundle\ClientBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PickerControllerTestTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $repository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $filterCollectionBuilder;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $baseHelper;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->repository = $this->getMockBuilder('\Tickit\Bundle\ClientBundle\Doctrine\Repository\ClientRepository')
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $this->filterCollectionBuilder = $this->getMockFilterCollectionBuilder();
        $this->baseHelper = $this->getMockBaseHelper();
    }

    /**
     * Tests the findAction() method
     *
     * @dataProvider getFindActionFixtures
     */
    public function testFindAction($term, array $expectedData)
    {
        $iterator = new \ArrayIterator(['filter' => 'value']);
        $paginator = $this->getMockPaginator();
        $filters = new FilterCollection();
        $decorator = $this->getMockObjectCollectionDecorator();
        $request = Request::create('', 'GET', ['term' => $term]);

        $this->filterCollectionBuilder->expects($this->once())
                                      ->method('buildFromArray')
                                      ->with($expectedData, new ClientFilterMapper(), FilterCollection::JOIN_TYPE_OR)
                                      ->will($this->returnValue($filters));

        $expectedFilters = new FilterCollection();
        $this->repository->expects($this->once())
                         ->method('findByFilters')
                         ->with($expectedFilters)
                         ->will($this->returnValue($paginator));

        $this->baseHelper->expects($this->once())
                         ->method('getObjectCollectionDecorator')
                         ->will($this->returnValue($decorator));

        $paginator->expects($this->once())
                  ->method('getIterator')
                  ->will($this->returnValue($iterator));

        $decorated = ['decorated' => 'values'];
        $decorator->expects($this->once())
                  ->method('setPropertyMappings')
                  ->with(['name' => 'text']);
        $decorator->expects($this->once())
                  ->method('decorate')
                  ->with($iterator, ['id', 'name'])
                  ->will($this->returnValue($decorated));

        $response = $this->getController()->findAction($request);

        /** @var JsonResponse $response */
        $this->assertInstanceOf('\Symfony\Component\HttpFoundation\JsonResponse', $response);
        $this->assertEquals(json_encode($decorated), $response->getContent());
    }

    /**
     * @return array
     */
    public function getFindActionFixtures()
    {
        return [
            ['Apple', ['name' => 'Apple']]
        ];
    }

    /**
     * Gets a new controller instance
     *
     * @return PickerController
     */
    private function getController()
    {
        return new PickerController($this->repository, $this->baseHelper, $this->filterCollectionBuilder);
    }
}
