# SEO Migration

Migrate from ether/seo to Retour and SEOMatic.
Currently only supports static redirects and per-page title and description.

## Usage

To migrate static redirects from ether/seo to Retour:

```
./craft seo-migration/migrate-retour
```

To migrate per-page title and description overrides from ether/seo to SEOMatic, you need an "SEO Settings Field" on the affected section.
Then, for each section, call the following command:

```
./craft seo-migration/migrate-seomatic/content --section [section] --from [oldSeoFieldName] --to [newSeoFieldName]
```

To migrate sitemap settings, call the following command:

```
./craft seo-migration/migrate-seomatic/sitemaps
```

After migrating, the old SEO field, the old SEO plugin and this migration plugin can be removed.

## Requirements

This plugin requires Craft CMS 4.12.0 or later, and PHP 8.0.2 or later.

## Installation

You can install this plugin from the Plugin Store or with Composer.

#### From the Plugin Store

Go to the Plugin Store in your project’s Control Panel and search for “SEO Migration”. Then press “Install”.

#### With Composer

Open your terminal and run the following commands:

```bash
# go to the project directory
cd /path/to/my-project.test

# tell Composer to load the plugin
composer require fork/craft-seo-migration

# tell Craft to install the plugin
./craft plugin/install seo-migration
```
