<?php
/**
 * forked from https://github.com/bpolaszek/webpack-encore-resolver
 */

namespace Project\WebpackEncoreResolver;

/**
 * @param string      $entrypoint
 * @param string|null $directory
 *
 * @return array<string>
 */
function encore_entry_js_files(string $entrypoint, string $directory = null): array
{
    return (new AssetPathResolver($directory))->getWebpackJsFiles($entrypoint);
}

/**
 * @param string      $entrypoint
 * @param string|null $directory
 *
 * @return array<string>
 */
function encore_entry_css_files(string $entrypoint, string $directory = null): array
{
    return (new AssetPathResolver($directory))->getWebpackCssFiles($entrypoint);
}

/**
 * @param string      $entrypoint
 * @param string|null $directory
 *
 * @return string
 */
function encore_entry_script_tags(string $entrypoint, string $directory = null): string
{
    return (new AssetPathResolver($directory))->renderWebpackScriptTags($entrypoint);
}

/**
 * @param string      $entrypoint
 * @param string|null $directory
 *
 * @return string
 */
function encore_entry_link_tags(string $entrypoint, string $directory = null): string
{
    return (new AssetPathResolver($directory))->renderWebpackLinkTags($entrypoint);
}

/**
 * @param string      $resource
 * @param string|null $directory
 *
 * @return string
 */
function asset(string $resource, string $directory = null): string
{
    return (new AssetPathResolver($directory))->getAssetPath($resource);
}

/**
 * @param string      $resource
 * @param string|null $directory
 *
 * @return string
 */
function assetIcon(string $resource, string $directory = null): string
{
    return (new AssetPathResolver($directory))->getAssetIcon($resource);
}
