<?php

namespace Kanboard\Plugin\Bugzilla;

use Kanboard\Core\Base;

class BugzillaClient extends Base
{
    public function getBaseUrl()
    {
        return $this->configModel->get('bugzilla_url');
    }

    public function getBug($uri)
    {
        $bugs = $this->httpClient->getJson($uri);
        if (!isset($bugs['bugs'])) {
            return null;
        }

        return $bugs['bugs'][0];
    }

    public function getBugComments($uri, $new_since = null)
    {
        $id = $this->getBugIdFromUri($uri);
        if ($id) {
            $commentsUri = sprintf('%s/rest/bug/%s/comment', $this->getBaseUrl(), $id);
            if (isset($new_since)) {
                $commentsUri .= '?new_since=' . $new_since;
            }

            $comments = $this->httpClient->getJson($commentsUri);
            $comments = $comments['bugs'][$id]['comments'];

            return $comments;
        }

        return null;
    }

    public function getBugHistory($uri, $new_since = null)
    {
        $id = $this->getBugIdFromUri($uri);
        if ($id) {
            $historyUri = sprintf('%s/rest/bug/%s/history', $this->getBaseUrl(), $id);
            if (isset($new_since)) {
                $historyUri .= '?new_since=' . $new_since;
            }

            $history = $this->httpClient->getJson($historyUri);
            $history = $history['bugs'][0]['history'];

            return $history;
        }

        return null;
    }

    public function getShowUrl($uri)
    {
        $id = $this->getBugIdFromUri($uri);
        if ($id) {
            return sprintf('%s/show_bug.cgi?id=%s', $this->getBaseUrl(), $id);
        }

        return null;
    }

    public function getApiUrl($id)
    {
        return sprintf('%s/rest/bug/%s', $this->getBaseUrl(), $id);
    }

    protected function getBugIdFromUri($uri)
    {
        if (preg_match('/\/bug\/(\d+)$/', $uri, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
