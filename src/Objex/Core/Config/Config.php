<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 08.01.18
 * Time: 17:42
 */

namespace Objex\Core\Config;


use Symfony\Component\Finder\Finder;

class Config
{
    private $config = [];

    public function __construct()
    {
        $this->config = $this->getProcessedDefaultConfig();
    }

    /**
     * @return array
     */
    public function getProcessedDefaultConfig()
    {
        $finder = new Finder();
        $finder->files()->in(base_path('config'));

        $config = [];

        foreach ($finder as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $name = str_replace('.php', '', $file->getFilename());

            try {
                $content = include $file;

                if (is_array($content)) {
                    $config = array_merge($config, [$name => $content]);
                }
            }
            catch(\Exception $e) {

            }

        }

        return $config;
    }

    /**
     * @param string $name
     * @return array
     */
    public function getConfig(string $name): array
    {
        if (! array_key_exists($name, $this->config)) {
            return [];
        }

        return $this->config[$name];
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = array_merge($this->config, $config);
    }
}