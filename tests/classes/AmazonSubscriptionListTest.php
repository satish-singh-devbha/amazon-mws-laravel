<?php

use KeithBrink\AmazonMws\AmazonSubscriptionList;
use PHPUnit\Framework\TestCase;

class AmazonSubscriptionListTest extends TestCase
{
    /**
     * @var AmazonSubscriptionList
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        resetLog();
        $this->object = new AmazonSubscriptionList('testStore', true, null);
    }

    public function testFetchSubscriptions()
    {
        resetLog();
        $this->object->setMock(true, 'fetchSubscriptionList.xml');
        $this->assertNull($this->object->fetchSubscriptions());
        $o = $this->object->getOptions();
        $this->assertEquals('ListSubscriptions', $o['Action']);

        $check = parseLog();
        $this->assertEquals('Single Mock File set: fetchSubscriptionList.xml', $check[1]);
        $this->assertEquals('Fetched Mock File: fetchSubscriptionList.xml', $check[2]);

        return $this->object;
    }

    /**
     * @param  AmazonSubscriptionList  $o
     * @depends testFetchSubscriptions
     */
    public function testGetList($o)
    {
        $list = $o->getList();
        $this->assertIsArray($list);
        $this->assertCount(2, $list);
        $this->assertEquals($o->getList(0), $list[0]);
        $this->assertEquals($o->getList(1), $list[1]);
        $this->assertIsArray($list[0]);
        $this->assertIsArray($list[1]);
    }

    /**
     * @param  AmazonSubscriptionList  $o
     * @depends testFetchSubscriptions
     */
    public function testGetNotificationType($o)
    {
        $this->assertEquals('AnyOfferChanged', $o->getNotificationType(0));
        $this->assertEquals('FulfillmentOrderStatus', $o->getNotificationType(1));
        $this->assertEquals($o->getNotificationType(0), $o->getNotificationType());
        //invalid keys
        $this->assertFalse($o->getNotificationType(4));
        $this->assertFalse($o->getNotificationType('no'));
        //not fetched yet for this object
        $this->assertFalse($this->object->getNotificationType());
    }

    /**
     * @param  AmazonSubscriptionList  $o
     * @depends testFetchSubscriptions
     */
    public function testGetIsEnabled($o)
    {
        $this->assertEquals('true', $o->getIsEnabled(0));
        $this->assertEquals('false', $o->getIsEnabled(1));
        $this->assertEquals($o->getIsEnabled(0), $o->getIsEnabled());
        //invalid keys
        $this->assertFalse($o->getIsEnabled(4));
        $this->assertFalse($o->getIsEnabled('no'));
        //not fetched yet for this object
        $this->assertFalse($this->object->getIsEnabled());
    }

    /**
     * @param  AmazonSubscriptionList  $o
     * @depends testFetchSubscriptions
     */
    public function testGetDeliveryChannel($o)
    {
        $this->assertEquals('SQS', $o->getDeliveryChannel(0));
        $this->assertEquals('SQS2', $o->getDeliveryChannel(1));
        $this->assertEquals($o->getDeliveryChannel(0), $o->getDeliveryChannel());
        //invalid keys
        $this->assertFalse($o->getDeliveryChannel(4));
        $this->assertFalse($o->getDeliveryChannel('no'));
        //not fetched yet for this object
        $this->assertFalse($this->object->getDeliveryChannel());
    }

    /**
     * @param  AmazonSubscriptionList  $o
     * @depends testFetchSubscriptions
     */
    public function testGetAttributes($o)
    {
        $data1 = [
            'sqsQueueUrl' => 'https://sqs.us-east-1.amazonaws.com/51471EXAMPLE/mws_notifications',
        ];
        $data2 = [
            'url' => 'https://sqs.us-west-1.amazonaws.com/51471EXAMPLE/mws_notifications',
            'something' => '7',
        ];
        $this->assertEquals($data1, $o->getAttributes(0));
        $this->assertEquals($data2, $o->getAttributes(1));
        $this->assertEquals($o->getAttributes(0), $o->getAttributes());
        //invalid keys
        $this->assertFalse($o->getAttributes(4));
        $this->assertFalse($o->getAttributes('no'));
        //not fetched yet for this object
        $this->assertFalse($this->object->getAttributes());
    }
}

require_once __DIR__.'/../helperFunctions.php';
