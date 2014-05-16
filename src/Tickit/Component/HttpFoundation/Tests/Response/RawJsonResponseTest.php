<?php

namespace Tickit\Component\HttpFoundation\Tests\Response;

use Tickit\Component\HttpFoundation\Response\RawJsonResponse;

/**
 * RawJsonResponse tests
 *
 * @package Tickit\Component\HttpFoundation\Tests\Response
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class RawJsonResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getRawJsonData
     */
    public function testConstructor($data)
    {
        $response = new RawJsonResponse($data);

        $this->assertEquals($data, $response->getContent());
    }

    /**
     * @dataProvider getRawJsonData
     */
    public function testSetData($data)
    {
        $response = new RawJsonResponse();
        $response->setData($data);

        $this->assertEquals($data, $response->getContent());
    }

    public function getRawJsonData()
    {
        $data = array(
            'property' => 1,
            'hello' => 'something',
            'object' => new \stdClass()
        );

        return [
            [json_encode($data)]
        ];
    }
}
