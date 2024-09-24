<?php

namespace fork\craftseomigration\services;

use Craft;
use craft\elements\Entry;
use craft\events\BatchElementActionEvent;
use craft\services\Elements;
use ether\seo\records\SitemapRecord;
use nystudio107\seomatic\Seomatic as SeomaticPlugin;
use yii\base\Component;

/**
 * Seomatic service
 */
class Seomatic extends Component
{
    /**
     * Migrate section sitemap settings from ether/seo to SEOmatic
     *
     * @return void
     */
    public function migrateSitemaps()
    {
        /** @var \craft\console\Application|\craft\web\Application */
        $app = Craft::$app;

        /** @var SitemapRecord[] */
        $sitemaps = SitemapRecord::find()
            ->where(['group' => 'sections'])
            ->all();

        $siteId = $app->sites->primarySite->id;

        foreach ($sitemaps as $sitemap) {
            $metaBundle = SeomaticPlugin::getInstance()->metaBundles->getMetaBundleBySourceId(
                'section',
                $sitemap->url,
                $siteId
            );

            $metaBundle->metaSitemapVars->sitemapChangeFreq = $sitemap->frequency;
            $metaBundle->metaSitemapVars->sitemapPriority = $sitemap->priority;
            $metaBundle->metaSitemapVars->sitemapUrls = $sitemap->enabled;

            SeomaticPlugin::getInstance()->metaBundles->updateMetaBundle($metaBundle, $siteId);
        }
    }

    /**
     * Migrate title and description content SEO from ether/seo to SEOmatic
     *
     * @param string $section Section handle
     * @param string $fieldFrom ether/seo SEO field handle
     * @param string $fieldTo SEOmatic SEO Settings Field handle
     * @return void
     */
    public function migrateContent(string $section, string $fieldFrom, string $fieldTo)
    {
        /** @var \craft\console\Application|\craft\web\Application */
        $app = Craft::$app;

        $elementsService = $app->getElements();
        $entryQuery = Entry::find()->section($section);

        $elementsService->on(
            Elements::EVENT_BEFORE_RESAVE_ELEMENT,
            static function(BatchElementActionEvent $e) use ($entryQuery, $fieldFrom, $fieldTo) {
                if ($e->query === $entryQuery) {
                    $element = $e->element;

                    /** @var \nystudio107\seomatic\models\MetaBundle */
                    $dest = &$element->{$fieldTo};

                    /** @var \ether\seo\models\data\SeoData */
                    $src = $element->{$fieldFrom};

                    $srcTitle = reset($src->titleRaw);

                    if ($srcTitle !== $element->title) {
                        $dest->metaGlobalVars->overrides['seoTitle'] = true;
                        $dest->metaGlobalVars->seoTitle = $srcTitle;
                    }

                    if (!empty($src->descriptionRaw)) {
                        $dest->metaGlobalVars->overrides['seoDescription'] = true;
                        $dest->metaGlobalVars->seoDescription = $src->descriptionRaw;
                    }
                }
            }
        );

        $elementsService->resaveElements(
            $entryQuery,
            true,
            true
        );
    }
}
