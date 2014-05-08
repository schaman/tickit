<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tickit\Bundle\UserBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
use Tickit\Bundle\UserBundle\Form\Type\FilterFormType;
use Tickit\Component\Filter\Collection\FilterCollection;
use Tickit\Component\Filter\Map\User\UserFilterMapper;
use Tickit\Component\Test\AbstractUnitTest;
use Tickit\Bundle\UserBundle\Controller\ApiController;
use Tickit\Bundle\UserBundle\Controller\UserController;
use Tickit\Component\Model\User\User;

/**
 * ApiController tests
 *
 * @package Tickit\Bundle\UserBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiControllerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $baseHelper;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $csrfHelper;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $filterBuilder;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $userRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $avatarAdapter;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $paginator;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $formHelper;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $form;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $serializer;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->baseHelper    = $this->getMockBaseHelper();
        $this->csrfHelper    = $this->getMockCsrfHelper();
        $this->filterBuilder = $this->getMockFilterCollectionBuilder();

        $this->userRepository = $this->getMockBuilder('\Tickit\Bundle\UserBundle\Doctrine\Repository\UserRepository')
                                     ->disableOriginalConstructor()
                                     ->getMock();

        $this->avatarAdapter = $this->getMock('Tickit\Component\Avatar\Adapter\AvatarAdapterInterface');
        $this->paginator = $this->getMockPaginator();
        $this->formHelper = $this->getMockFormHelper();
        $this->form = $this->getMockForm();
        $this->serializer = $this->getMockSerializer();
    }
    
    /**
     * Tests the fetchAction() method
     */
    public function testFetchActionBuildsResponseForUser()
    {
        $user = $this->getUser();

        $this->baseHelper->expects($this->never())
                         ->method('getUser');

        $this->trainBaseHelperToReturnSerializer();

        $serializedValue = 'serialized user';
        $this->trainSerializerToReturnSerializedValue($user, $serializedValue);

        $response = $this->getController()->fetchAction($user);
        $this->assertEquals($serializedValue, $response->getContent());
    }

    /**
     * Tests the fetchAction() method
     */
    public function testFetchActionBuildsResponseForCurrentUserWhenNoUserSpecified()
    {
        $user = $this->getUser();

        $this->baseHelper->expects($this->once())
                         ->method('getUser')
                         ->will($this->returnValue($user));

        $serializedValue = 'serialized user';
        $this->trainBaseHelperToReturnSerializer();
        $this->trainSerializerToReturnSerializedValue($user, $serializedValue);

        $response = $this->getController()->fetchAction();
        $this->assertEquals($serializedValue, $response->getContent());
    }

    /**
     * Tests the listAction() method
     */
    public function testListActionBuildsCorrectResponse()
    {
        $request = new Request();
        $filterData = [
            'key' => 'value'
        ];
        $this->trainBaseHelperToReturnRequest($request);
        $filters = new FilterCollection();

        $this->formHelper->expects($this->once())
                         ->method('createForm')
                         ->with(new FilterFormType(), null)
                         ->will($this->returnValue($this->form));

        $this->form->expects($this->once())
                   ->method('getData')
                   ->will($this->returnValue($filterData));

        $this->filterBuilder->expects($this->once())
                            ->method('buildFromArray')
                            ->with($filterData, new UserFilterMapper())
                            ->will($this->returnValue($filters));

        $user1 = new User();
        $user1->setUsername('user1');
        $user2 = new User();
        $user2->setUsername('user2');
        $users = [$user1, $user2];

        $this->trainPaginatorToReturnIterator($users);
        $this->trainPaginatorToReturnCount(2);

        $this->userRepository->expects($this->once())
                             ->method('findByFilters')
                             ->with($filters)
                             ->will($this->returnValue($this->paginator));

        $decorator = $this->getMockObjectCollectionDecorator();
        $this->baseHelper->expects($this->once())
                         ->method('getObjectCollectionDecorator')
                         ->will($this->returnValue($decorator));

        $this->csrfHelper->expects($this->once())
                         ->method('generateCsrfToken')
                         ->with(UserController::CSRF_DELETE_INTENTION)
                         ->will($this->returnValue(new CsrfToken('id', 'token-value')));

        $decorator->expects($this->once())
                  ->method('decorate')
                  ->with(
                      new \ArrayIterator($users),
                      ['id', 'forename', 'surname', 'email', 'username', 'admin', 'lastActivity'],
                      ['csrf_token' => 'token-value']
                  )
                  ->will($this->returnValue([['decorated user'], ['decorated user']]));

        $expectedData = ['data' => [['decorated user'], ['decorated user']], 'total' => 2, 'pages' => 1, 'currentPage' => 1];
        $response = $this->getController()->listAction(1);
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Gets a controller instance
     *
     * @return ApiController
     */
    private function getController()
    {
        return new ApiController(
            $this->baseHelper,
            $this->csrfHelper,
            $this->filterBuilder,
            $this->userRepository,
            $this->avatarAdapter,
            $this->formHelper
        );
    }

    private function getUser()
    {
        $user = new User();
        $user->setForename('forename')
             ->setSurname('surname')
             ->setEmail('forename.surname@domain.com')
             ->setId(1)
             ->setUsername('username');

        return $user;
    }

    private function trainAvatarAdapterToReturnUrl(\PHPUnit_Framework_MockObject_MockObject $adapter, User $user)
    {
        return $adapter->expects($this->once())
                       ->method('getImageUrl')
                       ->with($user, 35)
                       ->will($this->returnValue('avatar-url'));
    }

    private function trainBaseHelperToReturnRequest(Request $request)
    {
        $this->baseHelper->expects($this->once())
                         ->method('getRequest')
                         ->will($this->returnValue($request));
    }

    private function trainBaseHelperToReturnObjectDecorator(\PHPUnit_Framework_MockObject_MockObject $decorator)
    {
        $this->baseHelper->expects($this->once())
                         ->method('getObjectDecorator')
                         ->will($this->returnValue($decorator));
    }

    private function trainObjectDecoratorToExpectUserData(
        \PHPUnit_Framework_MockObject_MockObject $objectDecorator,
        User $user,
        array $returnData
    ) {
        $objectDecorator->expects($this->once())
                        ->method('decorate')
                        ->with(
                            $user,
                            ['id', 'username', 'email', 'forename', 'surname'],
                            ['avatarUrl' => 'avatar-url']
                        )
                        ->will($this->returnValue($returnData));
    }

    private function trainPaginatorToReturnIterator(array $data)
    {
        $this->paginator->expects($this->once())
                        ->method('getIterator')
                        ->will($this->returnValue(new \ArrayIterator($data)));
    }

    private function trainPaginatorToReturnCount($count)
    {
        $this->paginator->expects($this->once())
                        ->method('count')
                        ->will($this->returnValue($count));
    }

    private function trainBaseHelperToReturnSerializer()
    {
        $this->baseHelper->expects($this->once())
                         ->method('getSerializer')
                         ->will($this->returnValue($this->serializer));
    }

    private function trainSerializerToReturnSerializedValue($user, $serializedValue)
    {
        $this->serializer->expects($this->once())
                         ->method('serialize')
                         ->with($user)
                         ->will($this->returnValue($serializedValue));
    }
}
