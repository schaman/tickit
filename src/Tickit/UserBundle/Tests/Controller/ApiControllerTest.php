<?php

namespace Tickit\UserBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tickit\CoreBundle\Filters\Collection\FilterCollection;
use Tickit\CoreBundle\Tests\AbstractUnitTest;
use Tickit\UserBundle\Controller\ApiController;
use Tickit\UserBundle\Controller\UserController;
use Tickit\UserBundle\Entity\User;

/**
 * ApiController tests
 *
 * @package Tickit\UserBundle\Tests\Controller
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
    private $avatar;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->baseHelper = $this->getMockBaseHelper();
        $this->csrfHelper = $this->getMockCsrfHelper();
        $this->filterBuilder = $this->getMockFilterCollectionBuilder();

        $this->userRepository = $this->getMockBuilder('\Tickit\UserBundle\Entity\Repository\UserRepository')
                                     ->disableOriginalConstructor()
                                     ->getMock();

        $this->avatar = $this->getMockBuilder('Tickit\UserBundle\Avatar\AvatarService')
                             ->disableOriginalConstructor()
                             ->getMock();
    }
    
    /**
     * Tests the fetchAction() method
     */
    public function testFetchActionBuildsResponseForUser()
    {
        $user = $this->getUser();

        $this->baseHelper->expects($this->never())
                         ->method('getUser');

        $adapter = $this->getMockForAbstractClass('Tickit\UserBundle\Avatar\Adapter\AvatarAdapterInterface');
        $this->trainAvatarAdapterToReturnUrl($adapter, $user);

        $this->avatar->expects($this->once())
                     ->method('getAdapter')
                     ->will($this->returnValue($adapter));

        $expectedData = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'forename' => $user->getForename(),
            'surname' => $user->getSurname(),
            'avatarUrl' => 'avatar-url'
        ];

        $objectDecorator = $this->getMockObjectDecorator();
        $this->trainObjectDecoratorToExpectUserData($objectDecorator, $user, $expectedData);
        $this->trainBaseHelperToReturnObjectDecorator($objectDecorator);

        $response = $this->getController()->fetchAction($user);
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
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

        $adapter = $this->getMockForAbstractClass('Tickit\UserBundle\Avatar\Adapter\AvatarAdapterInterface');
        $this->trainAvatarAdapterToReturnUrl($adapter, $user);

        $this->avatar->expects($this->once())
                     ->method('getAdapter')
                     ->will($this->returnValue($adapter));

        $expectedData = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'forename' => $user->getForename(),
            'surname' => $user->getSurname(),
            'avatarUrl' => 'avatar-url'
        ];

        $objectDecorator = $this->getMockObjectDecorator();
        $this->trainObjectDecoratorToExpectUserData($objectDecorator, $user, $expectedData);
        $this->trainBaseHelperToReturnObjectDecorator($objectDecorator);

        $response = $this->getController()->fetchAction();
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Tests the listAction() method
     */
    public function testListActionBuildsCorrectResponse()
    {
        $request = new Request();
        $this->trainBaseHelperToReturnRequest($request);
        $filters = new FilterCollection();

        $this->filterBuilder->expects($this->once())
                            ->method('buildFromRequest')
                            ->with($request)
                            ->will($this->returnValue($filters));

        $user1 = new User();
        $user1->setUsername('user1');
        $user2 = new User();
        $user2->setUsername('user2');
        $users = [$user1, $user2];

        $this->userRepository->expects($this->once())
                             ->method('findByFilters')
                             ->with($filters)
                             ->will($this->returnValue($users));

        $decorator = $this->getMockObjectCollectionDecorator();
        $this->baseHelper->expects($this->once())
                         ->method('getObjectCollectionDecorator')
                         ->will($this->returnValue($decorator));

        $this->csrfHelper->expects($this->once())
                         ->method('generateCsrfToken')
                         ->with(UserController::CSRF_DELETE_INTENTION)
                         ->will($this->returnValue('token-value'));

        $decorator->expects($this->once())
                  ->method('decorate')
                  ->with(
                      $users,
                      ['id', 'forename', 'surname', 'email', 'username', 'lastActivity'],
                      ['csrf_token' => 'token-value']
                  )
                  ->will($this->returnValue([['decorated user'], ['decorated user']]));

        $expectedData = [['decorated user'], ['decorated user']];
        $response = $this->getController()->listAction();
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
            $this->avatar
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
}
