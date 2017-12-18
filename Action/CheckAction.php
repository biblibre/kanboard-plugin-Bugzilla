<?php

namespace Kanboard\Plugin\Bugzilla\Action;

use Kanboard\Action\Base;
use Kanboard\Core\ExternalTask\NotFoundException;

class CheckAction extends Base
{
    public function getDescription()
    {
        return t('Check Bugzilla bugs for changes');
    }

    public function doAction(array $data)
    {
        $tasks = $this->taskFinderModel->getAll($this->getProjectId());
        $tasks = array_filter($tasks, function ($task) {
            return $task['external_provider'] == 'Bugzilla';
        });
        $provider = $this->externalTaskManager->getProvider('Bugzilla');
        foreach ($tasks as $task) {
            try {
                $t = $provider->fetch($task['external_uri']);
            } catch (NotFoundException $e) {
                $this->logger->info(sprintf('Bugzilla bug %d was removed (task #%d)', $task['reference'], $task['id']));
                continue;
            }

            $last_sync = $this->taskMetadataModel->get($task['id'], 'bugzilla_last_sync', '0');

            $changes = array();

            $newComments = $this->bugzillaClient->getBugComments($task['external_uri'], $last_sync);
            if (!empty($newComments)) {
                $changes[] = t('%d new comment(s)', count($newComments));
            }

            $newHistory = $this->bugzillaClient->getBugHistory($task['external_uri'], $last_sync);
            if (!empty($newHistory)) {
                foreach ($newHistory as $newHistoryItem) {
                    foreach ($newHistoryItem['changes'] as $change) {
                        if ($change['field_name'] !== 'cc') {
                            $changes[] = t('%s changed %s from %s to %s', $newHistoryItem['who'], $change['field_name'], $change['removed'], $change['added']);
                        }
                    }
                }
            }

            if (!empty($changes)) {
                $text = t('Changes since %s', $this->helper->dt->date($last_sync));
                $text .= "\n* " . implode("\n* ", $changes);
                $this->commentModel->create(array(
                    'user_id' => 0,
                    'task_id' => $task['id'],
                    'comment' => $text,
                ));

                $this->taskMetadataModel->save($task['id'], array(
                    'bugzilla_last_sync' => date('c'),
                ));

                $this->logger->info(sprintf('Bugzilla bug %d was updated (task #%d)', $task['reference'], $task['id']));
            }
        }
    }

    public function getActionRequiredParameters()
    {
        return array();
    }

    public function getEventRequiredParameters()
    {
        return array();
    }

    public function getCompatibleEvents()
    {
        return array('task.cronjob.daily');
    }

    public function hasRequiredCondition(array $data)
    {
        return true;
    }
}
