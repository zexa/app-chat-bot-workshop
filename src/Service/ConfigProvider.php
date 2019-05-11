<?php

namespace Service;

class ConfigProvider {
  private $configFile;

  public function __construct($configFile) {
    $this->configFile = $configFile;
  }

  public function getParameter($parameter) {
    $config = $this->parseConfig();
    
    return $config[$parameter];
  }

  public function parseConfig() {
    return json_decode(file_get_contents($this->configFile), true);
  }
}


?>
