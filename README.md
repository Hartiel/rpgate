# 🎲 RPGate - Virtual Tabletop & Social Network

**RPGate** é uma plataforma híbrida focada em simplificar a experiência de RPG de mesa, unindo uma rede social para descoberta de mesas altamente expansível.

## 🚀 Visão do Projeto
Criar um ecossistema onde mestres e jogadores possam automatizar sistemas simples e onde desenvolvedores possam disponibilizar suas criações através de mods/plugins.

## 🛠️ Tech Stack
- **Backend:** Python 3.11 | FastAPI
- **Frontend:** Vue.js 3 (Vite) | TypeScript
- **Banco de Dados:** PostgreSQL
- **Infraestrutura:** Docker & Docker Compose

## 📋 Roadmap de Prototipagem

### Fase 1: Social (MVP)
- [ ] Sistema de Autenticação (JWT)
- [ ] Perfil de Usuário (Foto, Bio, Atributos sociais)
- [ ] Gerenciamento de Salas (Criação, busca e listagem de mesas públicas/privadas)
- [ ] Feed de Atividades

### Fase 2: Motor de Jogo (VTT)
- [ ] Chat em Tempo Real (WebSockets)
- [ ] Sistema de Rolagem de Dados
- [ ] Fichas Dinâmicas (Persistência em JSONB)
- [ ] Sincronização de estado entre jogadores/mestre

## 📦 Instalação e Execução

### Pré-requisitos
- Docker e Docker Compose instalados.
- WSL2 configurado (se estiver no Windows).

### Passo a Passo
1. Clone o repositório:
   ```bash
   git clone [https://github.com/seu-usuario/rpgate.git](https://github.com/seu-usuario/rpgate.git)
   cd rpgate

2. Configure as variáveis de ambiente:
    ```bash
    cp .env.example .env
    ```

3. Suba os containers:
    ```bash
    docker-compose up --build
    ```