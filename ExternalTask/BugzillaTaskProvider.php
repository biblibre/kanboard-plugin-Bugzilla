<?php

namespace Kanboard\Plugin\Bugzilla\ExternalTask;

use Kanboard\Core\Base;
use Kanboard\Core\ExternalTask\ExternalTaskProviderInterface;

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

    public function fetch($uri)
    {
        $bug = $this->bugzillaClient->getBug($uri);
        $bug['comments'] = $this->bugzillaClient->getBugComments($uri);

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
        return $this->bugzillaClient->getApiUrl(trim($formValues['id']));
    }
}
