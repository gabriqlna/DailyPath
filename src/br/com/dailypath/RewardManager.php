<?php

namespace br\com\dailypath;

use pocketmine\player\Player;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\lang\Language;
use pocketmine\utils\Config;

class RewardManager {

    private Main $plugin;
    private Config $rewards;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
        $this->rewards = new Config($plugin->getDataFolder() . "rewards.yml", Config::YAML);
    }

    public function claimReward(Player $player): void {
        $uuid = $player->getUniqueId()->toString();
        
        // Processa lógica de dados
        $newStreak = $this->plugin->dataManager->processClaim($uuid);
        
        // 1. Recompensas Padrão
        $standards = $this->rewards->get("standard", []);
        foreach($standards as $cmd) {
            $this->executeCommand($cmd, $player);
        }

        // 2. Recompensas de Chance
        $chances = $this->rewards->get("chance_rewards", []);
        foreach($chances as $line) {
            // Formato: "chance:comando"
            $parts = explode(":", $line, 2);
            if(count($parts) < 2) continue;
            
            $probability = (float) $parts[0];
            $command = $parts[1];
            
            // Gera número entre 0 e 100
            if((mt_rand(1, 10000) / 100) <= $probability) {
                $this->executeCommand($command, $player);
            }
        }

        // 3. Recompensas de Milestone (Streak)
        $milestones = $this->rewards->get("milestones", []);
        if(isset($milestones[$newStreak])) {
            foreach($milestones[$newStreak] as $cmd) {
                $this->executeCommand($cmd, $player);
            }
            // Broadcast opcional
            $msg = $this->plugin->getConfig()->getNested("messages.broadcast_milestone");
            $msg = str_replace(["{PLAYER}", "{DAYS}"], [$player->getName(), $newStreak], $msg);
            $this->plugin->getServer()->broadcastMessage($msg);
        }

        // Som e Mensagem
        $player->sendMessage($this->plugin->getConfig()->getNested("messages.prefix") . 
                             $this->plugin->getConfig()->getNested("messages.claim_success"));
        
        $streakMsg = str_replace("{STREAK}", (string)$newStreak, 
                                 $this->plugin->getConfig()->getNested("messages.streak_info"));
        $player->sendMessage($this->plugin->getConfig()->getNested("messages.prefix") . $streakMsg);
        
        // Toca som
        $soundName = $this->plugin->getConfig()->getNested("settings.claim_sound");
        // Em PM5, sons são complexos de executar sem packets diretos ou eventos, 
        // mas podemos usar o comando /playsound via console para simplificar.
        if($soundName) {
            $this->executeCommand("playsound $soundName {PLAYER}", $player);
        }
    }

    private function executeCommand(string $cmd, Player $player): void {
        $cmd = str_replace("{PLAYER}", '"' . $player->getName() . '"', $cmd); // Aspas para nomes com espaço
        $this->plugin->getServer()->dispatchCommand(
            new ConsoleCommandSender($this->plugin->getServer(), new Language("eng")),
            $cmd
        );
    }
}
