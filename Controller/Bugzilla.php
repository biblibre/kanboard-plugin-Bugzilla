<?php

namespace Kanboard\Plugin\Bugzilla\Controller;

use Kanboard\Controller\BaseController;
use Kanboard\Plugin\Bugzilla\ExternalTask\BugzillaTaskProvider;

class Bugzilla extends BaseController {
    public function show () {
        $task = $this->getTask();
        if ($task['external_provider'] != 'Bugzilla' || !$task['external_uri']) {
            return $this->response->json(array('error' => 'not a bugzilla task'));
        }

        $provider = new BugzillaTaskProvider($this->container);
        $bugzillaTask = $provider->fetch($task['external_uri']);
        if (!$bugzillaTask) {
            return $this->response->json(array('error' => 'bugzilla issue not found'));
        }

        $bugzillaBug = $bugzillaTask->getBug();
        $response = array(
            'status' => $bugzillaBug['status'],
        );

        $this->response->json($response);
    }
}
