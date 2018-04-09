<?php

namespace RestBundle\Listener;

use RestBundle\Services\ImageLink;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;

class LinkSerializeListener implements EventSubscriberInterface
{
    protected $link;

    public function __construct(ImageLink $link)
    {
        $this->link = $link;
    }

    /**
     * @return array
     */
    static public function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.pre_serialize',
                'method' => 'serializeLink'
            ]
        ];
    }

    /**
     * @param ObjectEvent $event
     */
    public function serializeLink(ObjectEvent $event)
    {
        $this->link->setImageLink($event->getObject());
    }
}