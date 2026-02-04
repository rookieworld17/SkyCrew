<?php
/**
 * General Configuration
 *
 * All of your system's general configuration settings go in here. You can see a
 * list of the available settings in vendor/craftcms/cms/src/config/GeneralConfig.php.
 *
 * @see \craft\config\GeneralConfig
 * @link https://craftcms.com/docs/5.x/reference/config/general.html
 */

use craft\config\GeneralConfig;
use craft\helpers\App;

$server = getenv('PRIMARY_SITE_URL');
$publicPath = realpath(dirname(__DIR__) . '/web');

$isDev = App::env('CRAFT_ENVIRONMENT') === 'dev';
$isProd = App::env('CRAFT_ENVIRONMENT') === 'production';

$config = (GeneralConfig::create())
    ->aliases([
        'assetPath' => $publicPath . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR,
        'assetUrl' => $server . 'assets/',
        'basePath' => $publicPath . DIRECTORY_SEPARATOR,
        'baseUrl' => $server,
    ])
    ->phpSessionName('SkyCrewSession')
    ->sendPoweredByHeader(false)
    ->omitScriptNameInUrls(true)
     ->errorTemplatePrefix('_errors/')
    ->defaultWeekStartDay(1)
    ->showFirstAndLastNameFields(true)
    ->securityKey('a9fQ79groI3d7noWJR2-ixN0N2sAymlR')
    ->requireMatchingUserAgentForSession(false)
    ->allowAdminChanges(!$isProd)
    ->devMode(!$isProd)
    ->enableTemplateCaching(!$isDev);

return $config;