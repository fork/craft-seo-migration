<?php

namespace fork\craftseomigration\services;

use ether\seo\records\RedirectRecord;
use nystudio107\retour\Retour as RetourPlugin;
use yii\base\Component;

/**
 * Retour service
 */
class Retour extends Component
{
    /**
     * Checks if the match URI may contain a regex
     *
     * @param string $uri
     * @return bool
     */
    private static function _isRedirectRegex(string $uri)
    {
        // Contains a match group, e.g. (.*) or (.+)
        return preg_match('/\(.+\)/', $uri) === 1;
    }

    /**
     * Migrate static redirects from ether/seo to craft-retour
     *
     * @return bool Whether migration was successfull
     */
    public function migrate()
    {
        /** @var RedirectRecord[] */
        $redirects = RedirectRecord::find()
            ->orderBy('order asc')
            ->all();

        foreach ($redirects as $r) {
            $uri = $r->uri;
            if (!str_starts_with($uri, '/')) {
                $uri = "/{$uri}";
            }

            $redirectConfig = [
                'enabled' => true,
                'siteId' => $r->siteId,
                'redirectSrcUrl' => $uri,
                'redirectDestUrl' => $r->to,
                'redirectHttpCode' => $r->type,
                'redirectMatchType' => Retour::_isRedirectRegex($r->uri) ? 'regexmatch' : 'exactmatch',
            ];

            $res = RetourPlugin::getInstance()->redirects->saveRedirect($redirectConfig);
            if (!$res) {
                return false;
            }
        }

        return true;
    }
}
