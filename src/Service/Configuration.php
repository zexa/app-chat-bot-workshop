<?php

namespace Paysera\Workshop\ChatBot\Service;

class Configuration
{
    private $configFile;

    /**
     * @param string $configFile
     */
    public function __construct($configFile)
    {
        $this->configFile = $configFile;
    }

    /**
     * @param string $name
     * @return string
     */
    public function get($name)
    {
        return $this->getData()['config'][$name];
    }

    private function getData()
    {
        return json_decode(file_get_contents($this->configFile), true);
    }
}
