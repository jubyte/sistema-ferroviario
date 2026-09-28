![Banner](../assets/imgs/banner_xampp.png)

## 1. CONCEITO

O XAMPP é uma distribuição de software utilizada para criar um ambiente de desenvolvimento local para aplicações web. Ele reúne ferramentas que permitem executar e testar sistemas diretamente no computador, sem a necessidade de utilizar um servidor de hospedagem externo.

Entre os principais componentes presentes no XAMPP estão Apache, MariaDB, PHP e phpMyAdmin. A composição pode variar de acordo com a versão utilizada. :contentReference[oaicite:15]{index=15}

O XAMPP é utilizado principalmente por estudantes e desenvolvedores para criar, testar e corrigir aplicações web em um ambiente local.

## 2. PRINCIPAIS COMPONENTES

O XAMPP reúne diferentes ferramentas que trabalham em conjunto para permitir a criação e execução de aplicações web.

### 2.1. APACHE

O Apache HTTP Server é um servidor web de código aberto utilizado para receber solicitações dos navegadores e disponibilizar conteúdos e aplicações web.

No ambiente XAMPP, o Apache permite que projetos desenvolvidos localmente sejam acessados pelo navegador por meio de um endereço local, como `localhost`.

### 2.2. MYSQL/MARIADB

O MariaDB é um sistema de gerenciamento de banco de dados relacional utilizado para armazenar e organizar as informações de aplicações.

O XAMPP disponibiliza o MariaDB em suas versões atuais, enquanto versões anteriores do pacote utilizavam MySQL. :contentReference[oaicite:16]{index=16}

Em um sistema web, o banco de dados pode armazenar informações como usuários, produtos, registros e outras informações utilizadas pela aplicação.

### 2.3. PHP

PHP é uma linguagem de programação utilizada principalmente no desenvolvimento de aplicações web. O código PHP é executado no servidor e pode gerar páginas HTML dinamicamente.

No XAMPP, o PHP pode ser utilizado em conjunto com o Apache para executar aplicações desenvolvidas localmente.

### 2.4. PHPMYADMIN

O phpMyAdmin é uma ferramenta desenvolvida em PHP utilizada para administrar bancos de dados MySQL e MariaDB por meio de uma interface web.

Com ele, é possível criar bancos de dados, tabelas e registros, executar comandos SQL e realizar outras tarefas de gerenciamento.

## 3. COMO REALIZAR A INSTALAÇÃO

Para instalar o XAMPP, deve-se acessar o site oficial do projeto e baixar a versão compatível com o sistema operacional utilizado.

Durante a instalação, é possível selecionar os componentes que serão instalados. Em seguida, deve-se escolher o diretório de instalação e concluir o processo seguindo as instruções apresentadas pelo instalador.

Após a instalação, o XAMPP Control Panel pode ser utilizado para iniciar e interromper serviços, como o Apache e o banco de dados.

## 4. CONFIGURAÇÃO BÁSICA

Após instalar o XAMPP, alguns serviços precisam ser iniciados para que a aplicação funcione.

Para executar um projeto PHP localmente, normalmente é necessário:

- iniciar o **Apache**;
- iniciar o **MySQL/MariaDB**, caso o projeto utilize banco de dados;
- colocar os arquivos do projeto no diretório utilizado pelo servidor web;
- acessar o projeto pelo navegador utilizando `localhost`.

A configuração pode variar de acordo com o sistema operacional, a versão do XAMPP e as necessidades do projeto.

## 5. IMPORTÂNCIA DO AMBIENTE

O XAMPP é importante porque permite desenvolver e testar aplicações web localmente. Dessa forma, o programador pode executar códigos PHP, criar bancos de dados e testar funcionalidades antes de publicar o sistema em um servidor.

Entre suas principais utilizações estão:

- testar projetos localmente;
- executar aplicações PHP;
- criar e administrar bancos de dados;
- testar conexões entre PHP e banco de dados;
- estudar desenvolvimento web;
- identificar e corrigir erros antes da publicação.

## REFERÊNCIAS

APACHE FRIENDS. XAMPP. Disponível em: https://www.apachefriends.org/pt_br/index.html. Acesso em: 28 set. 2026.

APACHE FRIENDS. Download XAMPP. Disponível em: https://www.apachefriends.org/pt_br/download.html. Acesso em: 28 set. 2026.

IBM. O que é Apache Server? Disponível em: https://www.ibm.com/br-pt/think/topics/apache-server. Acesso em: 18 maio 2026.

ORACLE. O que é MySQL? Disponível em: https://www.oracle.com/br/mysql/what-is-mysql/. Acesso em: 18 maio 2026.

PHP DOCUMENTATION GROUP. Introdução ao PHP. *PHP Manual*. Disponível em: https://www.php.net/manual/pt_BR/introduction.php. Acesso em: 18 maio 2026.

PHPMYADMIN. phpMyAdmin. Disponível em: https://www.phpmyadmin.net/. Acesso em: 18 maio 2026.