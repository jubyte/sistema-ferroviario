![Imagem do banner](/assets/imgs/banner.png)

# MOCKING RAIL

Um sistema de monitoramento ferroviário desenvolvido como Trabalho de Conclusão do Curso Técnico em Desenvolvimento de Sistemas do SESI SENAI Joinville, Santa Catarina (2026).

O projeto simula uma empresa do setor ferroviário, envolvendo sensores, banco de dados e uma interface web para gerenciamento e acompanhamento das informações da operação.

## SOBRE

O Mocking Rail foi desenvolvido para centralizar informações relacionadas à operação ferroviária e facilitar o gerenciamento de todos os usuários, sensores, trens e rotas envolvidos nas operações da ferrovia.

O sistema também conta com uma página de introdução sobre história e funcionamento breves, área de login e uma interface interna para acesso às principais funcionalidades da plataforma.

### OBJETIVOS

* Facilitar o gerenciamento das informações da ferrovia;
* Centralizar os dados utilizados pelo sistema;
* Permitir o gerenciamento de usuários, sensores, trens e rotas com maior facilidade;
* Organizar as informações de forma simples, acessível e clara para qualquer um que tenha conhecimento básico do funcionamento da ferrovia;
* Auxiliar no acompanhamento das operações ferroviárias.

## FUNCIONALIDADES

| Módulo               | Descrição                                                    | Status    |
| -------------------- | ------------------------------------------------------------ | --------- |
| Página de introdução | Páginas de História, Objetivos e Sustentabilidade da empresa | Concluído |
| Login e Cadastro     | Acesso e cadastro de usuários do sistema                     | Concluído |
| Menu                 | Área principal com informações gerais do sistema             | Concluído |
| Usuários             | Cadastro, listagem, edição e exclusão de usuários            | Concluído |
| Trens                | Cadastro, listagem, edição e exclusão de sensores            | Concluído |
| Sensores             | Cadastro e gerenciamento das informações dos trens           | Concluído |
| Rotas                | Área destinada ao gerenciamento das rotas ferroviárias       | Concluído |


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
* APIs de clima e CEP

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
│   ├── previsao_clima.js               # Lógica de login e cadastro
│   ├── rotas.js               # Lógica de login e cadastro
│   ├── script.js               # Lógica de login e cadastro
│   ├── sensores.js             # Lógica de sensores
│   ├── trens.js               # Lógica de login e cadastro
│   └── usuarios.js             # Lógica de usuários
│
├── style/
│   └── style.css               # Estilos do sistema
│
├── index.php                   # Página inicial
├── LICENSE
└── README.md
```

## BANCO DE DADOS

O sistema utiliza **MySQL** para armazenar e organizar as informações utilizadas pela aplicação.

O script de criação do banco de dados está localizado em:

```text
database/bd.sql
```

Entre as principais tabelas utilizadas atualmente estão:

* `usuarios` — informações dos usuários cadastrados;
* `trens` — informações dos trens;
* `sensores` — informações dos sensores.
* `rotas` — informações dos rotas.

A conexão entre o sistema e o banco de dados é realizada pelo arquivo:

```text
infra/conexao.php
```

A aplicação utiliza **PDO** para realizar a comunicação com o banco de dados.

### Como executar

O projeto utiliza PHP e MySQL, sendo necessário um servidor local para sua execução.

### 1. Instale o XAMPP

Instale o **XAMPP** em seu computador.

### 2. Coloque o projeto no XAMPP

Copie a pasta do projeto para o diretório:

```text
C:\xampp\htdocs\
```

### 3. Inicie o servidor

Abra o painel do XAMPP e inicie os módulos:

* **Apache**
* **MySQL**

### 4. Crie o banco de dados

Acesse o **phpMyAdmin** pelo navegador:

```text
http://localhost/phpmyadmin
```

Importe o arquivo:

```text
database/bd.sql
```

Esse arquivo contém as instruções necessárias para criação do banco de dados e das tabelas utilizadas pelo sistema.

### 5. Acesse o sistema

Após iniciar o Apache e o MySQL, abra no navegador:

```text
http://localhost/sistema-ferroviario/
```

O sistema será iniciado pela página `index.php`.

## PÁGINA DE INTRODUÇÃO

A página inicial apresenta informações institucionais da Ferrorama Mocking Rail.

O sistema possui as seguintes páginas:

* **História:** apresenta informações sobre a história da empresa;
* **Objetivos:** apresenta os objetivos da empresa e do projeto;
* **Sustentabilidade:** apresenta informações relacionadas às práticas de sustentabilidade.

Essas páginas fazem parte da área institucional do sistema.

## LOGIN

O sistema possui uma área destinada ao acesso dos usuários.

A tela de **Login** permite que o usuário informe seus dados para acessar a área interna do sistema, como é um sistema voltado para a área empresarial e contém dados restritos a funcionários o cadastro de novos usuários deve ser realizado por um administrador.

## MENU

Após o acesso, o usuário será direcionado ao **Menu**, que funciona como a área principal da aplicação.

O menu permite acessar as principais funcionalidades do sistema:

* Usuários;
* Sensores;
* Trens;
* Rotas.

A tela também apresenta informações gerais da aplicação e serve como ponto de navegação entre as áreas do sistema.

## USUÁRIOS

A área de **Usuários** é destinada ao gerenciamento dos usuários cadastrados no sistema.

A funcionalidade contempla operações de:

* Cadastro;
* Listagem (com pesquisa);
* Edição;
* Exclusão.

Os arquivos responsáveis pelas operações do CRUD estão organizados na pasta:

```text
public/telas/crud_usuarios/
```

## SENSORES

A área de **Sensores** é destinada ao gerenciamento dos sensores utilizados pelo sistema.

As informações relacionadas aos sensores incluem:

* Nome;
* Tipo;
* Local;
* Status.

O CRUD de sensores está organizado na pasta:

```text
public/telas/crud_sensores/
```

A funcionalidade contempla operações de cadastro, listagem, edição e exclusão.

## TRENS

A área de **Trens** será utilizada para o gerenciamento das informações dos trens da empresa.

A tela possui campos relacionados às informações do trem, como:

* Nome;
* Empresa;
* Número de vagões;
* Tipo.

## ROTAS

A área de **Rotas** será utilizada para o gerenciamento das informações das rotas da ferrovia.

A tela possui campos relacionados às informações das rotas, como:

* Nome;
* Início;
* Fim;

## DOCUMENTAÇÃO

Os documentos utilizados durante o desenvolvimento do projeto estão armazenados na pasta:

```text
docs/
```

Entre eles estão:

* Manual do Usuário;
* Pesquisa sobre CRUD;
* Pesquisa sobre PDO;
* Pesquisa sobre Scrum;
* Pesquisa sobre identidade visual;
* Pesquisa sobre XAMPP.

## AUTORES

Projeto desenvolvido por estudantes do Curso Técnico em Desenvolvimento de Sistemas da escola SESI/SENAI Moinho.

* Eduarda Bosse de Miranda
* Guilherme Goll Figueiró
* Henrique Marques Tonioti
* Julia Barbosa Ferreira
