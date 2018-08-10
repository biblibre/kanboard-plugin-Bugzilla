<?php

namespace Kanboard\Plugin\Bugzilla\ExternalTask;

use Kanboard\Core\ExternalTask\ExternalTaskInterface;

class BugzillaTask implements ExternalTaskInterface
{
    protected $uri;
    protected $bug;

    public function __construct($uri, $bug)
    {
        $this->uri = $uri;
        $this->bug = $bug;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getBugId()
    {
        return $this->bug['id'];
    }

    public function getBug()
    {
        return $this->bug;
    }

    public function getFormValues()
    {
        $title = sprintf('Bug %d %s', $this->bug['id'], $this->bug['summary']);

        return array(
            'title' => $title,
            'reference' => $this->bug['id'],
        );
    }
}
