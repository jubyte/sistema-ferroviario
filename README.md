![Imagem do baner](/assets/imgs/banner.jpg)


#Mocking Rail
Sistema web de monitoramento ferroviário desenvolvido como Trabalho de Conclusão do Curso Técnico em Desenvolvimento de Sistemas do SESI/SENAI Santa Catarina (2026).
A plataforma simula uma solução usada por empresas do setor ferroviário, integrando sensores IoT, banco de dados e uma interface de gerenciamento para acompanhar, em tempo real, o desempenho da operação.

##Sobre o projeto
O Mocking Rail centraliza em painéis as informações operacionais de uma malha ferroviária: estado dos sensores, alertas, consumo de energia, temperatura, pressão dos freios e ocorrências de falha. A proposta é permitir que operadores e administradores identifiquem problemas rapidamente, planejem manutenções preventivas e tomem decisões com base em dados.
Objetivos
Monitorar em tempo real as condições dos trens e da infraestrutura
Antecipar falhas e apoiar a manutenção preventiva
Transformar grandes volumes de dados em indicadores claros
Oferecer uma interface simples para as equipes de operação

##Funcionalidades
Módulo
Descrição
Status
Login / Cadastro
Autenticação de usuários da plataforma
Em desenvolvimento
Menu
Visão geral com resumo de sensores, alertas, trens e indicadores do dia
Em desenvolvimento
Painel
Dashboard com gráficos e indicadores operacionais
Em desenvolvimento
Sensores
Cadastro, listagem e busca de sensores (nome, tipo, local, status)
Em desenvolvimento
Monitoramento
Acompanhamento de consumo, temperatura, pressão dos freios e falhas
Em desenvolvimento
Relatórios
Geração de relatórios analíticos da operação
Planejado
Usuários
CRUD de usuários (cadastro, edição, exclusão e listagem)
Em desenvolvimento
Site institucional
Páginas de História, Objetivos e Sustentabilidade
Concluído


##Tecnologias
Front-end
HTML5
CSS3 (folha de estilo própria em style/style.css)
JavaScript
Bootstrap 5.3.2
Back-end
PHP
MySQL

##Estrutura de pastas
mocking-rail/
├── assets/
│   └── icons/            # Logos e ícones do sistema
├── doc/                  # Documentação do TCC
├── js/                   # Scripts do front-end
│   ├── script.js         # Login e cadastro
│   ├── sensores.js       # Lógica da tela de sensores
│   └── usuarios.js       # Lógica da tela de usuários
├── pages/
│   ├── inicio/           # Páginas institucionais
│   │   ├── história.html
│   │   ├── objetivos.html
│   │   └── sustentabilidade.html
│   ├── telas/            # Área logada do sistema
│   │   ├── login.html
│   │   ├── menu.html
│   │   ├── painel.html
│   │   ├── sensores.html
│   │   ├── monitoramento.html
│   │   ├── relatorios.html
│   │   └── usuarios.html
│   └── php/              # Back-end
│       ├── usuarios.php
│       ├── listar_usuario.php
│       ├── editar_usuario.php
│       └── excluir_usuario.php
├── style/
│   └── style.css         # Estilos globais
├── index.html            # Página inicial
├── LICENSE
└── README.md

##Como executar
O projeto usa PHP e banco de dados, então precisa de um servidor local.
Instale o XAMPP (ou WAMP/Laragon).
Copie a pasta do projeto para o diretório htdocs:
  C:\xampp\htdocs\mocking-rail
Inicie os módulos Apache e MySQL no painel do XAMPP.
Acesse o phpMyAdmin em http://localhost/phpmyadmin e importe o script SQL do banco (disponível em doc/).
Confira as credenciais de conexão no arquivo de conexão PHP.
Abra no navegador:
  http://localhost/mocking-rail/index.html
As páginas institucionais (index.html, História, Objetivos e Sustentabilidade) funcionam abrindo o arquivo diretamente, sem servidor.

##Banco de dados
O sistema utiliza MySQL. As principais entidades previstas são:
usuarios — id, nome, e-mail, telefone, tipo (Administrador/Usuário), status
sensores — id, nome, tipo, local, status
leituras — id, sensor_id, valor, data/hora
trens — id, identificação, status

##Autores
Projeto desenvolvido por estudantes do Curso Técnico em Desenvolvimento de Sistemas — SESI/SENAI Santa Catarina.

Eduarda Bosse
Guilherme Figueiró
Henrique Tonioti
Julia Barbosa
