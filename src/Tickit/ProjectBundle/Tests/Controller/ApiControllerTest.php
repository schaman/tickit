<?php

namespace Tickit\ProjectBundle\Tests\Controller;

use Guzzle\Tests\Http\Message\RequestTest;
use Symfony\Component\HttpFoundation\Request;
use Tickit\CoreBundle\Decorator\DomainObjectArrayDecorator;
use Tickit\CoreBundle\Decorator\DomainObjectDecoratorInterface;
use Tickit\CoreBundle\Filters\Collection\FilterCollection;
use Tickit\CoreBundle\Tests\AbstractUnitTest;
use Tickit\ProjectBundle\Controller\ApiController;
use Tickit\ProjectBundle\Controller\ProjectController;
use Tickit\ProjectBundle\Entity\ChoiceAttribute;
use Tickit\ProjectBundle\Entity\LiteralAttribute;
use Tickit\ProjectBundle\Entity\Project;

/**
 * ApiControllerTest tests
 *
 * @package Tickit\ProjectBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiControllerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $filterBuilder;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $projectRepo;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $attributeRepo;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $baseHelper;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $csrfHelper;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->filterBuilder = $this->getMockFilterCollectionBuilder();

        $this->projectRepo = $this->getMockBuilder('Tickit\ProjectBundle\Entity\Repository\ProjectRepository')
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $this->attributeRepo = $this->getMockBuilder('Tickit\ProjectBundle\Entity\Repository\AttributeRepository')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->baseHelper = $this->getMockBaseHelper();
        $this->csrfHelper = $this->getMockCsrfHelper();
    }
    
    /**
     * Tests the listAction() method
     */
    public function testListActionBuildsCorrectResponse()
    {
        $request = new Request();
        $filters = new FilterCollection();

        $this->trainBaseHelperToReturnRequest($request);
        $this->trainFilterBuilderToReturnFilters($filters, $request);

        $project1 = new Project();
        $project1->setName('Project 1');
        $project2 = new Project();
        $project2->setName('Project 2');
        $projects = array($project1, $project2);

        $this->projectRepo->expects($this->once())
                          ->method('findByFilters')
                          ->with($filters)
                          ->will($this->returnValue($projects));

        $decorator = $this->getMockObjectDecorator();
        $this->trainBaseHelperToReturnObjectDecorator($decorator);

        $this->csrfHelper->expects($this->once())
                         ->method('generateCsrfToken')
                         ->with(ProjectController::CSRF_DELETE_INTENTION)
                         ->will($this->returnValue('csrf-token-value'));

        $decorator->expects($this->exactly(2))
                  ->method('decorate')
                  ->will($this->returnValue(array('project')));

        $decorator->expects($this->at(0))
                  ->method('decorate')
                  ->with($project1, array('id', 'name', 'created'), array('csrf_token' => 'csrf-token-value'));

        $decorator->expects($this->at(1))
                  ->method('decorate')
                  ->with($project2, array('id', 'name', 'created'), array('csrf_token' => 'csrf-token-value'));

        $expectedData = array(array('project'), array('project'));
        $response = $this->getController()->listAction();
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Tests the attributesListAction() method
     */
    public function testAttributesListActionBuildsCorrectResponse()
    {
        $request = new Request();
        $filters = new FilterCollection();

        $this->trainBaseHelperToReturnRequest($request);
        $this->trainFilterBuilderToReturnFilters($filters, $request);

        $attribute1 = new LiteralAttribute();
        $attribute1->setName('attribute 1');
        $attribute2 = new ChoiceAttribute();
        $attribute2->setName('attribute 2');
        $attributes = [$attribute1, $attribute2];

        $this->attributeRepo->expects($this->once())
                            ->method('findByFilters')
                            ->with($filters)
                            ->will($this->returnValue($attributes));

        $decorator = $this->getMockObjectDecorator();
        $this->trainBaseHelperToReturnObjectDecorator($decorator);

        $decorator->expects($this->exactly(2))
                  ->method('decorate')
                  ->will($this->returnValue(array('attribute')));

        $decorator->expects($this->at(0))
                  ->method('decorate')
                  ->with($attribute1, array('id', 'type', 'name'));

        $decorator->expects($this->at(1))
                  ->method('decorate')
                  ->with($attribute2, array('id', 'type', 'name'));

        $expectedData = array(array('attribute'), array('attribute'));
        $response = $this->getController()->attributesListAction();
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Gets the controller instance
     *
     * @return ApiController
     */
    private function getController()
    {
        return new ApiController(
            $this->filterBuilder,
            $this->projectRepo,
            $this->attributeRepo,
            $this->baseHelper,
            $this->csrfHelper
        );
    }

    private function trainBaseHelperToReturnRequest(Request $request)
    {
        $this->baseHelper->expects($this->once())
                         ->method('getRequest')
                         ->will($this->returnValue($request));
    }

    private function trainFilterBuilderToReturnFilters(FilterCollection $filters, Request $request)
    {
        $this->filterBuilder->expects($this->once())
                            ->method('buildFromRequest')
                            ->with($request)
                            ->will($this->returnValue($filters));
    }

    private function trainBaseHelperToReturnObjectDecorator(DomainObjectDecoratorInterface $decorator)
    {
        $this->baseHelper->expects($this->once())
                         ->method('getObjectDecorator')
                         ->will($this->returnValue($decorator));
    }
}
