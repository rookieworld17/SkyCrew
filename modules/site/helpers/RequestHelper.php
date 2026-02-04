<?php

/**
 * Request-related helper utilities for the Site module.
 *
 * Provides a utility to resolve the Vite development server address from the
 * current request using a permissive lookup order suitable for local
 * development.
 *
 * Lookup order for the `vite-dev-server` value:
 * 1) HTTP header `vite-dev-server`
 * 2) Cookie `vite-dev-server`
 * 3) Query parameter `vite-dev-server`
 *
 * Notes:
 * - Intended for development workflows; treat the result as untrusted input
 *   in production and validate/sanitize as needed.
 * - Relies on Craft CMS request APIs.
 *
 * @package modules\site\helpers
 * @since 2025-11-28
 */

namespace modules\site\helpers;

/**
 * Helper for extracting request-scoped values used by the Site module.
 */
class RequestHelper
{
    /**
     * Get the Vite dev server address from the current request.
     *
     * Attempts to read the `vite-dev-server` value from, in order:
     * - Request headers
     * - Cookies
     * - Query parameters
     *
     * If none are present, returns `null`.
     *
     * Example header:
     * `vite-dev-server: http://localhost:5173`
     *
     * Security: This value is client-controlled and should be treated as
     * untrusted input. Use primarily in development or validate before use in
     * production environments.
     *
     * @return string|null The resolved dev server URL/host or null if not provided.
     */
    public static function getDevServer(): ?string
    {
        $request = \Craft::$app->getRequest();
        $varName = 'vite-dev-server';
        $devServer = $request->getHeaders()->get($varName, $_COOKIE[$varName] ?? null);
        if (!$devServer) {
            $devServer = $request->getCookies()->get($varName);
            if (!$devServer) {
                $devServer = $request->getQueryParam($varName);
            }
        }

        return $devServer;
    }
}