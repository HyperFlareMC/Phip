<?php

declare(strict_types=1);

namespace HyperFlareMC\phip;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class PhipCommand extends Command{

    /**
     * @var Phip
     */
    public Phip $phip;

    public function __construct(Phip $plugin){
        $this->phip = $plugin;
        parent::__construct(
            "phip",
            "Phip your server!",
            TextFormat::RED . "Usage: " . TextFormat::GRAY . "/phip [(plugin name)/all]",
            ["pharunzip"]
        );
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if($sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "No phipping for you! Silly goose!");
            return;
        }
        if(isset($args[0])){
            if($args[0] === "all"){
                $this->phip->executeAllPhip();
            }else{
                if(!$this->phip->getServer()->getPluginManager()->getPlugin($args[0])){
                    $sender->sendMessage("Invalid plugin! Try again!");
                    return;
                }
                $this->phip->executeSpecificPhip($args[0]);
            }
        }else{
            $sender->sendMessage($this->getUsage());
        }
    }

}