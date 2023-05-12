<?php
/**
 * forked from https://github.com/bpolaszek/webpack-encore-resolver
 */

namespace Project\WebpackEncoreResolver;

use Exception;

final class AssetPathResolver
{
    private string $directory;

    /**
     * @var array<string,array<string,string>>|null
     */
    private ?array $entryPoints = null;

    /**
     * @var array<string,bool>
     */
    private static array $filesMap = [];

    /**
     * @var array<string,string>|null
     */
    private ?array $manifest = null;

    public function __construct(?string $directory = null)
    {
        $this->directory = $directory ?? rex_path::frontend('build');
    }

    /**
     * @return array<string,array<string,string>>
     * @throws Exception
     */
    private function getEntryPoints(): array
    {
        if (null === $this->entryPoints) {
            $file = $this->directory.DIRECTORY_SEPARATOR.'entrypoints.json';
            $content = @file_get_contents($file);
            if (null === $content) {
                throw new Exception(
                    sprintf('Unable to read file "%s"', $file)
                );
            }

            /** @var array{entrypoints: array<string,array<string,string>>} $json */
            $json = json_decode($content, true);
            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new Exception(
                    sprintf('Unable to decode json file "%s"', $file)
                );
            }
            $this->entryPoints = $json['entrypoints'];
        }

        return $this->entryPoints;
    }

    /**
     * @return array<string,string>
     * @throws Exception
     */
    private function getManifest(): array
    {
        if (null === $this->manifest) {
            $file = $this->directory.DIRECTORY_SEPARATOR.'manifest.json';
            $content = @file_get_contents($file);
            if (null === $content) {
                throw new Exception(
                    sprintf('Unable to read file "%s"', $file)
                );
            }

            /** @var array<string,string> $json */
            $json = json_decode($content, true);
            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new Exception(
                    sprintf('Unable to decode json file "%s"', $file)
                );
            }

            $this->manifest = $json;
        }

        return $this->manifest;
    }

    /**
     * @param string $entrypoint
     *
     * @return array<string>
     * @throws Exception
     */
    public function getWebpackJsFiles(string $entrypoint): array
    {
        if (!array_key_exists($entrypoint, $this->getEntryPoints())) {
            throw new \InvalidArgumentException(
                sprintf('Invalid entrypoint "%s"', $entrypoint)
            );
        }

        /** @var array<string,array<string>> $files */
        $files = $this->getEntryPoints()[$entrypoint];

        return $files['js'] ?? [];
    }

    /**
     * @param string $entrypoint
     *
     * @return array<string>
     * @throws Exception
     */
    public function getWebpackCssFiles(string $entrypoint): array
    {
        if (!array_key_exists($entrypoint, $this->getEntryPoints())) {
            throw new \InvalidArgumentException(
                sprintf('Invalid entrypoint "%s"', $entrypoint)
            );
        }

        /** @var array<string,array<string>> $files */
        $files = $this->getEntryPoints()[$entrypoint];

        return $files['css'] ?? [];
    }

    /**
     * @param string $entrypoint
     *
     * @return string
     */
    public function renderWebpackScriptTags(string $entrypoint): string
    {
        $array = [];
        $files = $this->getWebpackJsFiles($entrypoint);
        foreach ($files as $file) {
            if (!isset(AssetPathResolver::$filesMap[$file])) {
                $array[] = sprintf('<script src="%s"></script>', $file);
                AssetPathResolver::$filesMap[$file] = true;
            }
        }
        return implode("\n", $array);
    }

    /**
     * @param string $entrypoint
     *
     * @return string
     */
    public function renderWebpackLinkTags(string $entrypoint): string
    {
        $array = [];
        $files = $this->getWebpackCssFiles($entrypoint);
        foreach ($files as $file) {
            if (!isset(AssetPathResolver::$filesMap[$file])) {
                $array[] = sprintf('<link rel="stylesheet" href="%s">', $file);
                AssetPathResolver::$filesMap[$file] = true;
            }
        }
        return implode("\n", $array);
    }

    /**
     * @param string $resource
     *
     * @return string
     */
    public function getAssetPath(string $resource): string
    {
        $withoutLeadingSlash = ltrim($resource, '/');
        $manifest = $this->getManifest();
        if (isset($manifest[$resource])) {
            return $manifest[$resource];
        }
        if (isset($manifest['build/'.$withoutLeadingSlash])) {
            return $manifest['build/'.$withoutLeadingSlash];
        }
        if (isset($manifest[$withoutLeadingSlash])) {
            return $manifest[$withoutLeadingSlash];
        }

        return $resource;
    }

    /**
     * @param string $resource
     *
     * @return string
     */
    public function getAssetIcon(string $resource): string
    {
        $iconPath = '/build/assets/icons/';
        $assetPath = $this->getAssetPath($iconPath.$resource.'.svg');
        return str_replace([$iconPath, '.svg'], '', $assetPath);
    }
}
