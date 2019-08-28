<?php

namespace Kanboard\Plugin\Bugzilla\ExternalTask;

use Kanboard\Core\Base;
use Kanboard\Core\ExternalTask\ExternalTaskProviderInterface;
use Kanboard\Core\ExternalTask\ExternalTaskException;
use Kanboard\Core\ExternalTask\NotFoundException;
use Kanboard\Model\TaskModel;

class BugzillaTaskProvider extends Base implements ExternalTaskProviderInterface
{
    public function getName()
    {
        return 'Bugzilla';
    }

    public function getIcon()
    {
        return '<i class="fa fa-bug fa-fw"></i>';
    }

    public function getMenuAddLabel()
    {
        return t('Add a new Bugzilla bug');
    }

    public function fetch($uri, $project_id)
    {
        $bug = $this->bugzillaClient->getBug($uri);
        if (!isset($bug)) {
            throw new NotFoundException("Bug not found");
        }

        return new BugzillaTask($uri, $bug);
    }

    public function save($uri, array $formValues, array &$formErrors)
    {
        return true;
    }

    public function getImportFormTemplate()
    {
        return 'Bugzilla:task/import';
    }

    public function getCreationFormTemplate()
    {
        return 'Bugzilla:task/creation';
    }

    public function getModificationFormTemplate()
    {
        return 'Bugzilla:task/modification';
    }

    public function getViewTemplate()
    {
        return 'Bugzilla:task/view';
    }

    public function buildTaskUri(array $formValues)
    {
        $taskUri = $this->bugzillaClient->getApiUrl(trim($formValues['id']));

        // If at task creation, check that no existing task in this project
        // already references this bug
        $controller = $this->request->getStringParam('controller');
        if ($controller === 'ExternalTaskCreationController') {
            $project_id = $this->request->getIntegerParam('project_id');

            $task = $this->db
                ->table(TaskModel::TABLE)
                ->eq(TaskModel::TABLE.'.project_id', $project_id)
                ->eq(TaskModel::TABLE.'.external_uri', $taskUri)
                ->findOne();

            if ($task) {
                throw new ExternalTaskException("An existing task (#{$task['id']}) already references this bug");
            }
        }

        return $taskUri;
    }
}
