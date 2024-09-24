<?php

namespace fork\craftseomigration;

use Craft;
use craft\base\Plugin;
use fork\craftseomigration\services\Retour;
use fork\craftseomigration\services\Seomatic;

/**
 * SEO Migration plugin
 *
 * @method static SeoMigration getInstance()
 * @author Fork Unstable Media GmbH <obj@fork.de>
 * @copyright Fork Unstable Media GmbH
 * @license MIT
 * @property-read Retour $retour
 * @property-read Seomatic $seomatic
 */
class SeoMigration extends Plugin
{
    public string $schemaVersion = '1.0.0';

    public static function config(): array
    {
        return [
            'components' => [
                'retour' => Retour::class,
                'seomatic' => Seomatic::class,
            ],
        ];
    }

    public function init(): void
    {
        parent::init();

        $this->attachEventHandlers();

        // Any code that creates an element query or loads Twig should be deferred until
        // after Craft is fully initialized, to avoid conflicts with other plugins/modules
        Craft::$app->onInit(function() {
            // ...
        });
    }

    private function attachEventHandlers(): void
    {
        // Register event handlers here ...
        // (see https://craftcms.com/docs/4.x/extend/events.html to get started)
    }
}
