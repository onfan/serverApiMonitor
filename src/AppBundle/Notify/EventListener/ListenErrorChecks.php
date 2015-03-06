<?php
/**
 * User: Joan Teixidó <joan@laiogurtera.com>
 * Date: 07/03/15
 * Time: 00:56
 */

namespace AppBundle\Notify\EventListener;


use AppBundle\EventListener\EventsName;
use AppBundle\EventListener\Not200Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ListenErrorChecks implements EventSubscriberInterface
{
    public function onError(Not200Event $event)
    {

    }

    /**
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            EventsName::STATUS_NOT_200 => array('onError')
        );
    }


}