![Imagem do banner](/assets/imgs/banner.jpg)

# MOCKING RAIL

Sistema de monitoramento ferroviário desenvolvido como Trabalho de Conclusão do Curso Técnico em Desenvolvimento de Sistemas do SESI SENAI Joinville, Santa Catarina (2026).

O projeto simula uma empresa do setor ferroviário, envolvendo sensores, banco de dados e uma interface web para gerenciamento e acompanhamento das informações da operação.

## SOBRE

O Mocking Rail foi desenvolvido para centralizar informações relacionadas à operação ferroviária e facilitar o gerenciamento de usuários, sensores, trens e rotas.

O sistema também conta com uma página institucional, área de login e cadastro e uma interface interna para acesso às principais funcionalidades da plataforma.

### OBJETIVOS

* Facilitar o gerenciamento das informações ferroviárias;
* Centralizar os dados utilizados pelo sistema;
* Permitir o gerenciamento de usuários, sensores, trens e rotas;
* Organizar as informações de forma simples e acessível;
* Auxiliar no acompanhamento das operações ferroviárias.

## FUNCIONALIDADES

| Módulo               | Descrição                                                    | Status             |
| -------------------- | ------------------------------------------------------------ | ------------------ |
| Página institucional | Páginas de História, Objetivos e Sustentabilidade da empresa | Concluído          |
| Login e Cadastro     | Acesso e cadastro de usuários do sistema                     | Concluído          |
| Menu                 | Área principal com informações gerais do sistema             | Em desenvolvimento |
| Usuários             | Cadastro, listagem, edição e exclusão de usuários            | Em desenvolvimento |
| Trens                | Cadastro, listagem, edição e exclusão de sensores            | Em desenvolvimento |
| Sensores             | Cadastro e gerenciamento das informações dos trens           | Em desenvolvimento |
| Rotas                | Área destinada ao gerenciamento das rotas ferroviárias       | Em desenvolvimento |

As funcionalidades marcadas como **Em desenvolvimento** já fazem parte do escopo do sistema e serão concluídas ao longo do desenvolvimento do projeto.

## TECNOLOGIAS

### Front-end

* HTML5
* CSS3
* JavaScript
* Bootstrap 5.3.2

### Back-end

* PHP
* MySQL
* PDO

## ESTRUTURA

```text
sistema-ferroviario/
│
├── assets/
│   ├── icons/                  # Ícones e elementos visuais
│   └── imgs/                   # Imagens utilizadas no sistema
│
├── database/
│   └── bd.sql                  # Script criação do banco de dados
│
├── docs/
│   ├── manual-do-usuario.md    # Manual do usuário
│   ├── pesquisa-crud.md        # Pesquisa sobre CRUD
│   ├── pesquisa-pdo.md         # Pesquisa sobre PDO
│   ├── pesquisa-scrum.md       # Pesquisa sobre Scrum
│   ├── pesquisa-visual.md      # Pesquisa sobre identidade visual
│   └── pesquisa-xampp.md       # Pesquisa sobre XAMPP
│
├── infra/
│   └── conexao.php             # Conexão com o banco de dados
│
├── public/
│   ├── inicio/
│   │   ├── historia.php        # Páginas institucionais
│   │   ├── objetivos.php      
│   │   └── sustentabilidade.php
│   │
│   └── telas/
│       ├── crud_sensores/
│       │   ├── cadastro_sensor.php
│       │   ├── editar_sensor.php
│       │   ├── excluir_sensor.php
│       │   └── listar_sensor.php
│       │
│       ├── crud_usuarios/
│       │   ├── cadastrar.php
│       │   ├── cadastro.php
│       │   ├── editar_usuario.php
│       │   ├── excluir_usuario.php
│       │   └── listar_usuario.php
│       │
│       ├── login.php           # Tela de login
│       ├── menu.php            # Menu principal
│       ├── rotas.php           # Tela de rotas
│       ├── sensores.php        # Tela de sensores
│       ├── trens.php           # Tela de trens
│       └── usuarios.php        # Tela de usuários
│
├── script/
│   ├── script.js               # Lógica de login e cadastro
│   ├── sensores.js             # Lógica de sensores
│   └── usuarios.js             # Lógica de usuários
│
├── style/
│   └── style.css               # Estilos do sistema
│
├── index.php                   # Página inicial
├── LICENSE
└── README.md
```