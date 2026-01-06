# 🌟 DailyPath

[![PocketMine-MP](https://img.shields.io/badge/PocketMine--MP-5.x-blue)](https://pmmp.io)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

**DailyPath** é um plugin de alta performance para PocketMine-MP 5.x focado em retenção de jogadores para servidores SMP (Survival Multi-Player). Ele gerencia recompensas diárias com um sistema inteligente de **Streaks** (dias consecutivos), incentivando os jogadores a entrarem no servidor todos os dias.

---

## 🚀 Funcionalidades

- **💎 Recompensas Flexíveis:** Entregue itens, XP, efeitos ou chaves através de comandos de console.
- **🔥 Sistema de Streak:** Acompanha quantos dias seguidos o jogador resgatou recompensas.
- **🎲 Probabilidades (Chances):** Configure itens raros que têm apenas uma porcentagem de chance de serem entregues.
- **🏆 Marcos de Streak (Milestones):** Defina recompensas especiais para quem atingir 7, 14 ou 30 dias seguidos.
- **🛡️ Anti-Exploit:** Identificação por **UUID** (impede resgate duplo trocando de Nick) e persistência de dados segura.
- **🕒 Fuso Horário Configurável:** Sincronizado com o horário de Brasília (`America/Sao_Paulo`) por padrão.
- **📱 GUI Intuitiva:** Interface gráfica limpa que mostra o estado atual da recompensa e o progresso do jogador.

---

## 🎁 Sistema de Recompensas (rewards.yml)

O plugin utiliza comandos do console para entregar prêmios, permitindo integração total com outros plugins (Economia, Crates, etc). Confira a estrutura do arquivo:

### **Standard** (Sempre entregues ao resgatar)
- `- "give {PLAYER} apple 5"`
- `- "xp 5L {PLAYER}"`

### **Chance Rewards** (Baseado em probabilidade / Sorte)
- `- "10:give {PLAYER} diamond 1"` → *10% de chance*
- `- "1:give {PLAYER} netherite_ingot 1"` → *1% de chance*

### **Milestones** (Prêmios por dias consecutivos / Streak)
- **Dia 7:**
  - `- "give {PLAYER} iron_block 1"`
  - `- "say {PLAYER} completou uma semana de streak!"`
- **Dia 30:**
  - `- "give {PLAYER} diamond_block 1"`
  - `- "title {PLAYER} title §6§lMESTRE DO SMP"`

---

> **Nota:** As recompensas são processadas via Console. Utilize o marcador `{PLAYER}` para que o plugin identifique automaticamente o jogador que realizou o resgate.


---

## 📊 Placeholders (Integrações)

O **DailyPath** oferece suporte nativo para integração com plugins de interface, permitindo que você exiba o progresso do jogador em tempo real.

### **ScoreHud**
Se você utiliza o [ScoreHud](https://poggit.pmmp.io/p/ScoreHud), pode adicionar a streak do jogador diretamente na sua scoreboard utilizando o seguinte placeholder:

- `{dailypath_streak}` — Exibe o número atual de dias consecutivos (streak) do jogador.

---

## 🛡️ Notas de Desenvolvimento (Poggit)

Este plugin foi construído seguindo rigorosamente as diretrizes de submissão e os padrões de qualidade do **Poggit**:

* **Async Data:** O plugin utiliza salvamento imediato e estruturado para evitar qualquer perda de progresso dos jogadores em caso de desligamentos inesperados.
* **No External Libs:** Não requer o download ou injeção de *virions* externos; o sistema de formulários (Forms) está incluído nativamente no código-fonte.
* **Main Thread Friendly:** Nenhuma tarefa pesada de processamento ou I/O bloqueante é realizada na thread principal durante o gameplay, garantindo o TPS estável.
* **Standard API:** Utiliza estritamente a API 5.0.0 do PocketMine-MP, garantindo compatibilidade total com as versões mais recentes.

---

> **Informação para Revisores:** O sistema de fuso horário é definido via `date_default_timezone_set` no `onEnable` para garantir consistência em servidores hospedados em diferentes regiões geográficas.


---


### **Como configurar no ScoreHud:**
1. Abra o arquivo `scoreboards.yml` do seu ScoreHud.
2. No local desejado, adicione a linha:
   `§eStreak: §f{dailypath_streak}`
3. Salve e use `/scorehud reload`.


---



## 📦 Como Instalar

1.  Baixe o arquivo `DailyPath.phar` da aba de [Releases](https://github.com/seu-usuario/DailyPath/releases).
2.  Mova o arquivo para a pasta `/plugins/` do seu servidor PocketMine-MP.
3.  Reinicie o servidor para carregar o plugin e gerar a pasta de dados.
4.  Configure o arquivo `rewards.yml` para definir os prêmios do seu servidor.
5.  Certifique-se de que os administradores possuem a permissão `dailypath.admin`.

---

## 🛠️ Comandos e Permissões

| Comando | Descrição | Permissão | Padrão |
| :--- | :--- | :--- | :--- |
| `/daily` | Abre a interface de recompensa diária. | `dailypath.use` | Todos |
| `/daily reload` | Recarrega as configurações (opcional). | `dailypath.admin` | OP |

---

## ⚙️ Configuração Principal (`config.yml`)

```yaml
settings:
  enable_streak: true # Ativa/Desativa contagem de dias seguidos
  streak_loss_action: "reset" # O que acontece se falhar um dia (reset, keep, reduce)
  claim_sound: "random.levelup" # Som ao resgatar
  timezone: "America/Sao_Paulo" # Fuso horário do servidor

messages:
  prefix: "§l§6Daily§ePath §r§8» §7"
  claim_success: "§aRecompensa resgatada com sucesso!"
  # ... (todas as outras mensagens são editáveis)
