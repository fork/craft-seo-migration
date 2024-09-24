<?php

namespace fork\craftseomigration\console\controllers;

use craft\console\Controller;
use fork\craftseomigration\SeoMigration;
use yii\console\ExitCode;

/**
 * Migrate Retour controller
 */
class MigrateRetourController extends Controller
{
    public $defaultAction = 'index';

    /**
     * Migrate static redirects from ether/seo to Retour.
     */
    public function actionIndex(): int
    {
        $res = SeoMigration::getInstance()->retour->migrate();
        if (!$res) {
            return ExitCode::UNSPECIFIED_ERROR;
        }

        return ExitCode::OK;
    }
}
