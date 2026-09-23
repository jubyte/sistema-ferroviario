![Banner](../assets/imgs/banner_xampp.png)

## 1. CONCEITO 

O PHP Data Objects (PDO) é uma extensão da linguagem PHP que define uma interface leve, consistente e orientada a objetos para o acesso a bancos de dados relacionais. Lançado a partir do PHP 5.1, o PDO atua como uma camada de abstração de acesso a dados (Data Access Abstraction Layer), permitindo que a aplicação se comunique com múltiplos sistemas de gerenciamento de banco de dados (SGBDs) utilizando um conjunto unificado de funções e métodos. 

## 2. PARA QUE ELE É UTILIZADO NO PHP 

O PDO é utilizado no PHP para gerenciar e executar operações em bancos de dados (como criação, leitura, atualização e remoção de dados — operações CRUD). Sua principal finalidade é padronizar a forma como o código PHP interage com a base de dados, independentemente do SGBD utilizado na infraestrutura da aplicação (seja MySQL, PostgreSQL, SQLite, SQL Server, entre outros).

## 3. COMO FUNCIONA UMA CONEXÃO UTILIZANDO PDO
A instalação do XAMPP é simples e pode ser realizada mesmo por pessoas com pouco conhecimento técnico, desde que o computador atenda aos requisitos necessários.

O primeiro passo é acessar o site oficial do XAMPP e baixar a versão compatível com o sistema operacional do computador, como Windows, Linux ou macOS.

Durante a instalação, o programa apresenta os componentes disponíveis, como Apache, MySQL, FileZilla e phpMyAdmin. O usuário pode escolher apenas os recursos que deseja utilizar.

Depois disso, é necessário selecionar a pasta onde os arquivos do XAMPP serão instalados e escolher o idioma do sistema. Em seguida, basta continuar o processo clicando em “Next” até a conclusão da instalação.

Após finalizar, o XAMPP estará pronto para ser utilizado no computador.


## 4. PRINCIPAIS CARACTERÍSTICAS 

Camada de Abstração de Acesso a Dados: Permite a reutilização de sintaxes e métodos do PHP independentemente do banco de dados utilizado.

Paradigma Orientado a Objetos: Toda a manipulação e o controle de erros ocorrem por meio de classes, métodos e exceções (PDOException).

Suporte Multi-Banco (Driver-Based): Funciona através de drivers específicos para cada SGBD (ex.: pdo_mysql, pdo_pgsql, pdo_sqlite).

Suporte Nativo a Prepared Statements: Oferece métodos integrados para a parametrização e execução segura de instruções SQL.

## 5. VANTAGENS E DESVANTAGENS 

**Vantagens:**

1) Portabilidade de Código: Facilita a migração de um banco de dados para outro sem a necessidade de reescrever todas as chamadas do código PHP.

2) Segurança: Prevenção nativa contra SQL Injection ao utilizar Prepared Statements.

3) Padronização: A equipe aprende apenas uma API para trabalhar com qualquer SGBD no ambiente PHP.

4) Tratamento Elegante de Erros: Utiliza exceções (Try-Catch), facilitando a captura e o tratamento de falhas em tempo de execução.

**Desvantagens:**

1) Curva de Aprendizado Ligeiramente Maior: Exige familiaridade com Orientação a Objetos.

2) Desempenho: Por ser uma camada de abstração genérica, pode apresentar uma diferença insignificante de velocidade em comparação ao MySQLi em cenários de altíssimo tráfego.

3) Não Abstrai a Linguagem SQL: Embora abstraia a conexão e o acesso aos dados, o PDO não traduz comandos SQL específicos de cada banco (como datas ou paginação).

## 6. DIFERENÇAS ENTRE PDO E MYSQLI

A escolha entre PDO e MySQLi envolve analisar critérios fundamentais de suporte, paradigma de programação, sintaxe, desempenho e controle de transações:

**- Suporte a Bancos de Dados:** O PDO destaca-se por sua flexibilidade, oferecendo suporte nativo a mais de 12 sistemas de gerenciamento de banco de dados diferentes (como MySQL, PostgreSQL, SQLite e SQL Server). Em contrapartida, o MySQLi é uma extensão desenvolvida exclusivamente para trabalhar com o banco de dados MySQL.

**- Paradigmas Suportados:** O PDO opera estritamente no paradigma de Programação Orientada a Objetos (POO). Já o MySQLi é mais versátil nesse aspecto, permitindo que o desenvolvedor utilize tanto a abordagem Orientada a Objetos quanto a abordagem Procedural (baseada em funções sequenciais).

**- Parâmetros Nomeados:** No tratamento de consultas preparadas, o PDO aceita tanto marcadores anônimos (?) quanto parâmetros nomeados (como :nome ou :email), o que facilita a leitura e a manutenção do código. O MySQLi, por sua vez, aceita apenas marcadores anônimos (?), exigindo a vinculação rigorosa por ordem posicional.


**- Desempenho (Performance):** Por ser uma extensão genérica que abstrai o acesso a múltiplos bancos, o PDO pode apresentar uma diferença insignificante de velocidade em consultas simples. O MySQLi tende a ser ligeiramente mais rápido ao interagir com o MySQL, pois foi otimizado especificamente para essa plataforma.

**- Tratamento de Transações:** Ambas as extensões oferecem suporte a transações complexas (commit e rollback). No entanto, o PDO realiza esse controle exclusivamente por meio de métodos nativos da sua classe principal, enquanto o MySQLi permite executá-lo tanto por métodos orientados a objetos quanto por funções procedurais.

## 7. PREPARED STATEMENTS 

O XAMPP é importante porque oferece um ambiente completo para desenvolvimento web, permitindo criar e testar sites e sistemas diretamente no computador, sem precisar contratar hospedagem. Ele reúne ferramentas como Apache, MySQL/MariaDB, PHP e phpMyAdmin em um único pacote, facilitando o desenvolvimento e os testes das aplicações. Além disso, ele oferece mais segurança, pois os testes podem ser feitos sem afetar usuários reais ou sistemas online.

Desse modo, é possível simular um servidor real, criar bancos de dados, executar códigos PHP e gerenciar informações de forma prática. Isso ajuda desenvolvedores e estudantes a testarem projetos antes da publicação na internet. Entre as principais vantagens do XAMPP estão:

- testar projetos localmente;
- criar bancos de dados;
- executar aplicações PHP;
- estudar e praticar desenvolvimento web.

## 8. SITUAÇÕES QUE O PDO PODE SER UMA BOA ESCOLHA 

Projetos de Médio e Grande Porte: Onde há possibilidade de alteração ou expansão da infraestrutura do banco de dados no futuro.

**- Aplicações Multi-tenant ou Multi-SGBD:** Sistemas que precisam interagir com diferentes bancos simultaneamente (ex.: MySQL para a aplicação principal e PostgreSQL ou SQLite para logs).
**- Desenvolvimento de APIs e Bibliotecas Reutilizáveis:** onde a flexibilidade de integração é um requisito essencial.
**- Sistemas que Priorizam Segurança:** Para garantir a obrigatoriedade da parametrização de dados e mitigar vulnerabilidades de segurança.


## REFERÊNCIAS

ASSOCIAÇÃO BRASILEIRA DE NORMAS TÉCNICAS (ABNT). NBR 6023: Informação e documentação - Referências - Elaboração. Rio de Janeiro: ABNT, 2018.  Acesso em: 09 set. 2026.

PHP DOCUMENTATION GROUP. PDO — PHP Data Objects. Manual do PHP, 2026. Disponível em: https://www.php.net/manual/pt_BR/book.pdo.php. Acesso em: 09 set. 2026.

RUBENS, Paulo. Desenvolvimento Web com PHP e Bancos de Dados Relacionais. 2. ed. São Paulo: Novatec, 2021.  Acesso em: 09 set. 2026.

SILVA, Mauricio Samy. PHP 8: Conceitos, Práticas e Integração com Banco de Dados. São Paulo: Érica, 2022.  Acesso em: 09 set. 2026.


