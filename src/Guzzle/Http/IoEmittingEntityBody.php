<?php

namespace Guzzle\Http;

use Guzzle\Common\Event;
use Guzzle\Common\HasDispatcherInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcher;
use Symfony\Contracts\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * EntityBody decorator that emits events for read and write methods
 */
class IoEmittingEntityBody extends AbstractEntityBodyDecorator implements HasDispatcherInterface
{
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    public static function getAllEvents()
    {
        return array('body.read', 'body.write');
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;

        return $this;
    }

    public function getEventDispatcher()
    {
        if (!$this->eventDispatcher) {
            $this->eventDispatcher = new EventDispatcher();
        }

        return $this->eventDispatcher;
    }

    public function dispatch(array $context = array(), $eventName)
    {
        return $this->getEventDispatcher()->dispatch($eventName, new Event($context));
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->getEventDispatcher()->addSubscriber($subscriber);

        return $this;
    }

    public function read($length)
    {
        $event = array(
            'body'   => $this,
            'length' => $length,
            'read'   => $this->body->read($length)
        );
        $this->dispatch($event, 'body.read');

        return $event['read'];
    }

    public function write($string)
    {
        $event = array(
            'body'   => $this,
            'write'  => $string,
            'result' => $this->body->write($string)
        );
        $this->dispatch($event, 'body.write');

        return $event['result'];
    }
}
