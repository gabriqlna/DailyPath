<?php

namespace br\com\dailypath;

use pocketmine\utils\Config;

class DataManager {
    
    private Main $plugin;
    private Config $data;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
        // Players.yml armazena: UUID: { last_claim: "Y-m-d", streak: int }
        $this->data = new Config($plugin->getDataFolder() . "data/players.yml", Config::YAML);
    }

    public function save(): void {
        $this->data->save();
    }

    public function getStreak(string $uuid): int {
        return $this->data->getNested("$uuid.streak", 0);
    }

    public function canClaim(string $uuid): bool {
        $lastClaimDate = $this->data->getNested("$uuid.last_claim", "");
        $today = date("Y-m-d");
        
        return $lastClaimDate !== $today;
    }

    /**
     * Atualiza os dados e retorna a nova streak
     */
    public function processClaim(string $uuid): int {
        $lastClaimDate = $this->data->getNested("$uuid.last_claim", "");
        $streak = $this->data->getNested("$uuid.streak", 0);
        $today = date("Y-m-d");
        
        // Lógica de Streak
        if ($this->plugin->getConfig()->getNested("settings.enable_streak")) {
            $yesterday = date("Y-m-d", strtotime("-1 day"));
            
            if ($lastClaimDate === $yesterday) {
                // Veio em dias consecutivos
                $streak++;
            } elseif ($lastClaimDate !== $today) {
                // Quebrou a streak (não veio ontem)
                $action = $this->plugin->getConfig()->getNested("settings.streak_loss_action", "reset");
                if ($action === "reset") {
                    $streak = 1; // Reseta para 1 (o dia atual)
                } elseif ($action === "reduce") {
                    $streak = max(1, $streak - 1);
                } else {
                    // keep
                    if ($streak == 0) $streak = 1;
                }
            }
        } else {
            // Streak desativada, apenas conta visualmente ou mantem 1
            $streak++;
        }

        $this->data->setNested("$uuid.last_claim", $today);
        $this->data->setNested("$uuid.streak", $streak);
        $this->data->save();

        return $streak;
    }
}
