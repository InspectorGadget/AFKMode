<?php

/**
	* All rights reserved RTGNetworkkk
	* GitHub: https://github.com/RTGNetworkkk
	* Author: InspectorGadget
*/

namespace RTG\AFK;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\utils\Config;

use pocketmine\event\Cancellable;

use pocketmine\Server;
use pocketmine\Player;

use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerChatEvent;

class AFK extends PluginBase implements Listener {
	
	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveResource("afk.txt");
		$this->saveResource("config.yml");
		$this->a = new Config($this->getDataFolder() . "afk.txt");
		$this->c = new Config($this->getDataFolder() . "config.yml");
		$this->getLogger()->warning("
		* Loading AFKMode...
		* Checking Version
		* 1.0.0 Passed!
		* AFKMode has been loaded!
		");
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
		switch(strtolower($cmd->getName())) {
			
			case "afkmode":
				if($sender instanceof Player) {
					if($sender->hasPermission("afkmode.toggle")) {
						$n = $sender->getName();
						
						if($this->a->get($n) === false) {
							$this->a->set($n);
							$this->a->save();
							$this->getServer()->broadcastMessage("§d[Server] [] $n is now AFK!");
						}
						else {
							$n = $sender->getName();
							if($this->a->get($n) === true) {
								$this->a->remove($n);
								$this->a->save();
								$this->getServer()->broadcastMessage("§d[Server] [] $n is no longer AFK!");
									if($this->c->get("bc") === true) {
										$this->getServer()->broadcastMessage("§d[Server] Thank you for using AFK by IG!");
									}
							}
						}
					}
					else {
						$sender->sendMessage("§cYou have no permission to use this command!");
					}
				}
				else {
					$sender->sendMessage("Only in-game!");
				}
				return true;
			break;
		}
	}
	
	public function onMove(PlayerMoveEvent $e) {
		$p = $e->getPlayer();
		if($this->a->get($p->getName()) === true) {
			$p->sendPopup("§cPlease do /afkmode to leave AFK Mode!");
			$e->setCancelled();
		}
	}
	
	public function onChat(PlayerChatEvent $e) {
		$p = $e->getPlayer();
		if($this->a->get($p->getName()) === true) {
			$p->sendMessage("§cPlease do /afkmode to leave AFK Mode!");
			$e->setCancelled();
		}
	}
	
}
