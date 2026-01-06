<?php

namespace br\com\dailypath;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use br\com\dailypath\utils\SimpleForm;

class Main extends PluginBase {

    public static Main $instance;
    public DataManager $dataManager;
    public RewardManager $rewardManager;

    public function onEnable(): void {
    	date_default_timezone_set('America/Sao_Paulo'); 
        self::$instance = $this;
        
        $this->saveDefaultConfig();
        $this->saveResource("rewards.yml");
        
        $this->dataManager = new DataManager($this);
        $this->rewardManager = new RewardManager($this);
        
        $this->getLogger()->info("DailyPath ativado com sucesso!");
    }

    public function onDisable(): void {
        if(isset($this->dataManager)) {
            $this->dataManager->save();
        }
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if($command->getName() === "daily") {
            if(!$sender instanceof Player) {
                $sender->sendMessage("Use este comando dentro do jogo.");
                return true;
            }
            $this->openDailyGUI($sender);
            return true;
        }
        return false;
    }

    public function openDailyGUI(Player $player): void {
        $uuid = $player->getUniqueId()->toString();
        $canClaim = $this->dataManager->canClaim($uuid);
        $streak = $this->dataManager->getStreak($uuid);
        $config = $this->getConfig();

        $form = new SimpleForm(function(Player $player, $data) use ($canClaim) {
            if($data === null) return;
            if($data === 0 && $canClaim) {
                $this->rewardManager->claimReward($player);
            }
        });

        $form->setTitle($config->getNested("gui.title"));
        
        if($canClaim) {
            $msg = str_replace("{STREAK}", (string)$streak, $config->getNested("gui.content_available"));
            $form->setContent($msg);
            $form->addButton($config->getNested("gui.button_claim"), 0, "textures/ui/confirm");
        } else {
            $msg = str_replace("{STREAK}", (string)$streak, $config->getNested("gui.content_unavailable"));
            $form->setContent($msg);
            $form->addButton($config->getNested("gui.button_close"), 0, "textures/ui/cancel");
        }

        $player->sendForm($form);
    }
    
    // API para ScoreHud
    public function getPlayerStreak(Player $player): int {
        return $this->dataManager->getStreak($player->getUniqueId()->toString());
    }
}
