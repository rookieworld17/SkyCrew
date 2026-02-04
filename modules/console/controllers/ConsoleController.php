<?php
/**
 * Console controller for custom Craft CMS console commands.
 *
 * This file defines the base console controller within the module's console
 * layer. It currently provides a default `index` action that returns a
 * successful exit code, and can be extended with additional actions for
 * project-specific console tasks.
 *
 * @package   modules\site\console\controllers
 * @since     2025-11-28
 */

namespace modules\site\console\controllers;

use craft\console\Controller;
use yii\console\ExitCode;

/**
 * Class ConsoleController
 *
 * Base console controller for module-related CLI actions.
 */
class ConsoleController extends Controller
{
    /**
     * Default console action.
     *
     * When invoked (e.g., via `php craft site-console/console/index` depending
     * on controller mapping), it simply returns `ExitCode::OK` to indicate
     * success. Extend this controller with additional actions for actual
     * console functionality.
     *
     * @return int One of the `yii\console\ExitCode` constants; `ExitCode::OK` on success.
     */
    public function actionIndex(): int
    {
        return ExitCode::OK;
    }
}