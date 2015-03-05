<?php
namespace Gantry\Framework\Base;

use Gantry\Component\Filesystem\Folder;
use RocketTheme\Toolbox\ArrayTraits\Export;
use RocketTheme\Toolbox\ArrayTraits\NestedArrayAccess;
use RocketTheme\Toolbox\DI\Container;

/**
 * The Platform Configuration class contains configuration information.
 *
 * @author RocketTheme
 * @license MIT
 */

abstract class Platform
{
    use NestedArrayAccess, Export;

    protected $items;
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;

        //Make sure that cache folder exists, otherwise it will be removed from the lookup.
        $cachePath = $this->getCachePath();
        Folder::create(GANTRY5_ROOT . '/' . $cachePath);

        $this->items = [
            'streams' => [
                // Cached files.
                'gantry-cache' => [
                    'type' => 'Stream',
                    'prefixes' => ['' => [$cachePath]]
                ],
                // Container for all frontend themes.
                'gantry-themes' => [
                    'type' => 'ReadOnlyStream',
                    'prefixes' => $this->getThemesPaths()
                ],
                // Selected frontend theme.
                'gantry-theme' => [
                    'type' => 'ReadOnlyStream',
                    'prefixes' => $this->getThemePaths()
                ],
                // System defined media files.
                'gantry-assets' => [
                    'type' => 'ReadOnlyStream',
                    'prefixes' => $this->getAssetsPaths()
                ],
                // User defined media files.
                'gantry-media' => [
                    'type' => 'ReadOnlyStream',
                    'prefixes' => $this->getMediaPaths()
                ],
                // Container for all Gantry engines.
                'gantry-engines' => [
                    'type' => 'ReadOnlyStream',
                    'prefixes' => $this->getEnginesPaths()
                ],
                // Gantry engine used to render the selected theme.
                'gantry-engine' => [
                    'type' => 'ReadOnlyStream',
                    'prefixes' => $this->getEnginePaths()
                ],
                // Layout definitions for the blueprints.
                'gantry-layouts' => [
                    'type' => 'ReadOnlyStream',
                    'prefixes' => ['' => ['gantry-theme://layouts']]
                ],
                // Gantry particles.
                'gantry-particles' => [
                    'type' => 'ReadOnlyStream',
                    'prefixes' => ['' => ['gantry-theme://particles', 'gantry-engine://particles']]
                ],
                // Gantry administration.
                'gantry-admin' => [
                    'type' => 'ReadOnlyStream',
                    'prefixes' => []
                ],
                // Blueprints for the configuration.
                'gantry-blueprints' => [
                    'type' => 'ReadOnlyStream',
                    'prefixes' => [
                        '' => ['gantry-engine://blueprints', 'gantry-theme://blueprints'],
                        'particles' => ['gantry-particles://']
                    ]
                ],
                // Configuration from the selected theme.
                'gantry-config' => [
                    'type' => 'ReadOnlyStream',
                    'prefixes' => ['' => ['gantry-theme://config']]
                ]
            ]
        ];
    }

    abstract public function getCachePath();
    abstract public function getThemesPaths();
    abstract public function getAssetsPaths();
    abstract public function getMediaPaths();

    public function getThemePaths()
    {
        return ['' => []];
    }

    public function getEnginePaths()
    {
        return ['' => []];
    }

    public function getEnginesPaths()
    {
        return ['' => []];
    }

    abstract public function settings();
}
