<?php

namespace Kanboard\Plugin\Bugzilla\Subscriber;

use Kanboard\Event\GenericEvent;
use Kanboard\Model\TaskModel;
use Kanboard\Subscriber\BaseSubscriber;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BugzillaSubscriber extends BaseSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            TaskModel::EVENT_CREATE => 'handleEvent',
        );
    }

    public function handleEvent(GenericEvent $event, $eventName)
    {
        $task = $event['task'];
        if ($task['external_provider'] === 'Bugzilla') {
            $this->taskMetadataModel->save($task['id'], array(
                'bugzilla_last_sync' => date('c'),
            ));

            $url = $this->bugzillaClient->getShowUrl($task['external_uri']);
            $this->taskExternalLinkModel->create(array(
                'task_id' => $task['id'],
                'url' => $url,
                'link_type' => 'weblink',
                'dependency' => 'related',
                'title' => sprintf('Bug %s', $task['reference']),
            ));
        }
    }
}
