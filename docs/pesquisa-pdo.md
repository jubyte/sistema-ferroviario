![Banner](../assets/imgs/banner_pdo.png)

## 1. CONCEITO

O PHP Data Objects (PDO) é uma extensão do PHP que fornece uma interface padronizada para acessar bancos de dados. Ele permite que aplicações PHP se comuniquem com diferentes Sistemas de Gerenciamento de Bancos de Dados (SGBDs) por meio de drivers específicos.

## 2. PARA QUE O PDO É UTILIZADO NO PHP

O PDO é utilizado para conectar aplicações PHP a bancos de dados e realizar operações como inserir, consultar, atualizar e excluir informações. Ele também permite utilizar Prepared Statements, contribuindo para a segurança das consultas.

## 3. COMO FUNCIONA UMA CONEXÃO UTILIZANDO PDO

A conexão é realizada por meio da classe `PDO`, informando o servidor, o banco de dados, o usuário e a senha.

```php
$pdo = new PDO(
    "mysql:host=localhost;dbname=meu_banco;charset=utf8mb4",
    "root",
    ""
);
```

Nesse exemplo, `localhost` indica o servidor local e `meu_banco` indica o banco de dados utilizado.

## 4. PRINCIPAIS CARACTERÍSTICAS

* **Interface padronizada:** facilita o acesso a diferentes bancos de dados;
* **Orientação a objetos:** utiliza classes e métodos do PHP;
* **Prepared Statements:** permite utilizar parâmetros nas consultas;
* **Tratamento de erros:** permite trabalhar com exceções, como `PDOException`;
* **Transações:** permite controlar operações com `commit()` e `rollBack()`.

## 5. VANTAGENS E DESVANTAGENS

### 5.1. VANTAGENS

1. **Segurança:** Prepared Statements ajudam a reduzir o risco de SQL Injection;
2. **Portabilidade:** permite trabalhar com diferentes SGBDs;
3. **Organização:** facilita a estruturação do acesso ao banco de dados;
4. **Tratamento de erros:** permite identificar e tratar problemas na conexão.

### 5.2. DESVANTAGENS

1. **Dependência de drivers:** é necessário utilizar o driver adequado para cada SGBD;
2. **Curva de aprendizado:** exige conhecimentos básicos de PHP e SQL;
3. **Não substitui o SQL:** o PDO não adapta automaticamente comandos específicos de um banco para outro.

## 6. DIFERENÇAS ENTRE PDO E MYSQLI

* **PDO:** pode trabalhar com diferentes SGBDs.
* **MySQLi:** é específico para MySQL.
* **PDO:** aceita parâmetros nomeados e posicionais.
* **MySQLi:** utiliza parâmetros posicionais.
* **Ambos:** possuem suporte a Prepared Statements e transações.

## 7. PREPARED STATEMENTS

Prepared Statements são consultas SQL preparadas que utilizam parâmetros para receber os valores durante a execução. No PDO, podem ser utilizados parâmetros nomeados ou posicionais.

```php
$sql = "SELECT * FROM usuarios WHERE email = :email";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    "email" => $email
]);
```

Nesse caso, o valor do e-mail é enviado separadamente da estrutura da consulta, ajudando a reduzir o risco de SQL Injection quando utilizado corretamente.

## 8. APLICAÇÃO NO PROJETO

No projeto, o PDO é utilizado para realizar a comunicação entre o sistema PHP e o banco de dados. Ele permite executar as operações do CRUD, como cadastrar, consultar, atualizar e excluir informações.

As consultas utilizam Prepared Statements para manter os valores recebidos separados da estrutura das instruções SQL.

## REFERÊNCIAS

PHP DOCUMENTATION GROUP. **PDO — PHP Data Objects**. PHP Manual. Disponível em: https://www.php.net/manual/pt_BR/book.pdo.php. Acesso em: 09 set. 2026.

PHP DOCUMENTATION GROUP. **Prepared Statements**. PHP Manual. Disponível em: https://www.php.net/manual/pt_BR/pdo.prepared-statements.php. Acesso em: 09 set. 2026.

PHP DOCUMENTATION GROUP. **PDO::prepare**. PHP Manual. Disponível em: https://www.php.net/manual/pt_BR/pdo.prepare.php. Acesso em: 09 set. 2026.
