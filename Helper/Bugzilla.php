<?php

namespace Kanboard\Plugin\Bugzilla\Helper;

use Kanboard\Core\Base;

class Bugzilla extends Base
{
    public function isBugzillaTask($task_id)
    {
        $task = $this->taskFinderModel->getById($task_id);

        return !empty($task['external_provider']) && $task['external_provider'] === 'Bugzilla';
    }
}
