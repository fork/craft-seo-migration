<?php

namespace fork\craftseomigration\console\controllers;

use craft\console\Controller;
use fork\craftseomigration\SeoMigration;
use yii\console\ExitCode;

/**
 * Migrate SEOmatic controller
 */
class MigrateSeomaticController extends Controller
{
    public ?string $section = null;
    public ?string $from = null;
    public ?string $to = null;

    public function options($actionID): array
    {
        $options = parent::options($actionID);
        switch ($actionID) {
            case 'content':
                $options[] = 'section';
                $options[] = 'from';
                $options[] = 'to';
                break;
        }
        return $options;
    }

    /**
     * Migrate sitemap settings from ether/seo to SEOmatic.
     */
    public function actionSitemaps(): int
    {
        SeoMigration::getInstance()->seomatic->migrateSitemaps();
        return ExitCode::OK;
    }

    /**
     * Migrate title and description content SEO from ether/seo to SEOmatic.
     */
    public function actionContent(): int
    {
        SeoMigration::getInstance()->seomatic->migrateContent(
            $this->section,
            $this->from,
            $this->to
        );

        return ExitCode::OK;
    }
}
