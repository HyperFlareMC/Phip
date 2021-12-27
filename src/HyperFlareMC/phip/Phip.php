<?php

declare(strict_types=1);

namespace HyperFlareMC\phip;

use DirectoryIterator;
use pocketmine\plugin\PluginBase;

class Phip extends PluginBase{

    protected function onEnable() : void{
        $this->getServer()->getCommandMap()->register("phip", new PhipCommand($this));
    }

    public function executeAllPhip() : void{
        $dir = $this->getDataFolder() . "extractions/all";
        if(is_dir($dir)){
            $this->emptyDir($dir);
            rmdir($dir);
            $this->getLogger()->info("Removed the old all folder, creating a new one.");
        }
        foreach(new DirectoryIterator($this->getServer()->getDataPath() . "plugins") as $file){
            if($file->isFile()){
                print $file->getFilename() . "\n";
                $phar = new \Phar($this->getServer()->getDataPath() . "/plugins/" . $file->getFileName());
                $phar->extractTo($this->getDataFolder() . "extractions/all/" . $file->getFileName());
            }
        }
    }

    public function executeSpecificPhip(string $plugin) : void{
        $dir = $this->getDataFolder() . "extractions/$plugin";
        if(is_dir($dir)){
            $this->emptyDir($dir);
            rmdir($dir);
            $this->getLogger()->info("Removing the old $plugin folder, creating a new one.");
        }
        $phar = new \Phar("server/plugins/$plugin.phar");
        $phar->extractTo($this->getDataFolder() . "extractions/" . $plugin);
    }

    public function emptyDir($dir) : void{
        if(is_dir($dir)){
            $scan = scandir($dir);
            foreach($scan as $files){
                if($files !== '.'){
                    if($files !== '..'){
                        if(!is_dir($dir . '/' . $files)){
                            unlink($dir . '/' . $files);
                        }else{
                            $this->emptyDir($dir . '/' . $files);
                            rmdir($dir . '/' . $files);
                        }
                    }
                }
            }
        }
    }

}