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
