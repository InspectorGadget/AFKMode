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

use pocketmine\event\Cancellable;

use pocketmine\Server;
use pocketmine\Player;

use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\utils\TextFormat as TF;

class AFK extends PluginBase implements Listener {
	
	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->enabled = array();
		$this->getLogger()->warning("
		* Loading AFKMode...
		* Checking Version
		* 1.0.1 Passed!
		* AFKMode has been loaded!
		");
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $param) {
		switch(strtolower($cmd->getName())) {
			
			case "afk":
				if($sender->hasPermission("afk.toggle") or $sender->isOp()) {
					
					if(isset($this->enabled[strtolower($sender->getName())])) {
						unset($this->enabled[strtolower($sender->getName())]);
						$sender->sendMessage("[God]" . TF::RED . " You have left AFKMode!");
					}
					else {
						$this->enabled[strtolower($sender->getName())] = strtolower($sender->getName());
						$sender->sendMessage("[God]" . TF::GREEN . " You have joined AFKMode!");
					}
					
				}
				else {
					$sender->sendMessage(TF::RED . "You have no permission to use this command!");
				}
				return true;
			break;
			
		}
	}
			
	public function onMove(PlayerMoveEvent $e) {
		$p = $e->getPlayer();
			
			if(isset($this->enabled[strtolower($p->getName())])) {
				$p->sendPopup(TF::RED . "Please leave AFKMode to walk!");
				$e->setCancelled(true);
			}
		
	}
			
	public function onChat(PlayerChatEvent $e) {
		$p = $e->getPlayer();
		
			if(isset($this->enabled[strtolower($p->getName())])) {
				$p->sendMessage(TF::RED . "Please leave AFKMode to chat!");
				$p->sendMessage(TF::GREEN . "You can leave AFKMode by typing /afk again!");
				$e->setCancelled(true);
			}
	
	}
	
	public function onJoin(PlayerJoinEvent $e) {
		$p = $e->getPlayer();
		
			if(isset($this->enabled[strtolower($p->getName())])) {
				unset($this->enabled[strtolower($p->getName())]);
				$sender->sendMessage(TF::RED . "Your mode has been reset!, you are no longer AFK!");
			}
			
	}
		
	public function onDisable() {
	}
	
}