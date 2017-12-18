<?php

namespace Kanboard\Plugin\Bugzilla;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;
use Kanboard\Plugin\Bugzilla\ExternalTask\BugzillaTaskProvider;
use Kanboard\Plugin\Bugzilla\Action\CheckAction;
use Kanboard\Plugin\Bugzilla\Subscriber\BugzillaSubscriber;

class Plugin extends Base
{
    public function initialize()
    {
        $this->template->hook->attach('template:config:integrations', 'Bugzilla:config/integration');

        $provider = new BugzillaTaskProvider($this->container);
        $this->externalTaskManager->register($provider);

        $action = new CheckAction($this->container);
        $this->actionManager->register($action);

        $subscriber = new BugzillaSubscriber($this->container);
        $this->dispatcher->addSubscriber($subscriber);
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__ . '/Locale');
    }

    public function getClasses()
    {
        $classes = array(
            'Plugin\Bugzilla' => array(
                'BugzillaClient',
            ),
        );

        return $classes;
    }

    public function getPluginName()
    {
        return 'Bugzilla';
    }

    public function getPluginDescription()
    {
        return t('Integration with Bugzilla');
    }

    public function getPluginAuthor()
    {
        return 'Julian Maurice';
    }

    public function getPluginVersion()
    {
        return '0.1.0';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/biblibre/kanboard-plugin-Bugzilla';
    }
}
