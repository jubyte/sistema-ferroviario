![Banner](../assets/imgs/banner_crud.png)

## 1. CONCEITO

CRUD é um conjunto de quatro operações básicas utilizadas para manipular dados em sistemas de software. O termo é formado pelas iniciais de Create, Read, Update e Delete, que representam, respectivamente, criar, consultar, atualizar e excluir dados.

Essas operações são utilizadas em sistemas que precisam armazenar e gerenciar informações, principalmente em conjunto com bancos de dados relacionais.

As quatro operações são:

- **Create (INSERT):** responsável pela inserção de novos dados no sistema, como o cadastro de usuários;
- **Read (SELECT):** utilizada para consultar e visualizar informações armazenadas;
- **Update (UPDATE):** permite modificar dados já existentes;
- **Delete (DELETE):** realiza a remoção de dados que não são mais necessários. Essa remoção também pode ser lógica, quando o registro permanece armazenado, mas é marcado como inativo.

### 1.1. OPERAÇÕES

As operações CRUD estão presentes em grande parte dos sistemas digitais. Sempre que um usuário cadastra, consulta, altera ou exclui uma informação, uma ou mais dessas operações são utilizadas.

O CRUD também contribui para a organização do sistema, pois estabelece uma estrutura básica para a interação entre a aplicação e o banco de dados. Dessa forma, facilita o gerenciamento e a manutenção das informações.

## 2. APLICAÇÃO NO PROJETO

No projeto desenvolvido, o CRUD é utilizado para organizar e controlar os dados recebidos pelos sensores dos trens. As informações coletadas são armazenadas no banco de dados e podem ser manipuladas pela aplicação.

A operação **Create** é utilizada para registrar novas informações enviadas pelos sensores, como localização, velocidade e consumo de energia.

Por meio da operação **Read**, é possível consultar os dados armazenados e visualizar as informações coletadas pelo sistema.

A operação **Update** permite atualizar informações já existentes. Isso pode ocorrer, por exemplo, na alteração do status de um trem ou na correção de um dado cadastrado incorretamente.

Já a operação **Delete** permite remover informações que não são mais necessárias, como registros duplicados ou dados que não precisam permanecer no sistema. Dependendo da regra definida para o projeto, essa exclusão também pode ser realizada de forma lógica.

## REFERÊNCIAS

MOZILLA. CRUD. *MDN Web Docs*, 2025. Disponível em: https://developer.mozilla.org/pt-BR/docs/Glossary/CRUD. Acesso em: 4 maio 2026.

ESCOLA DNC. CRUD: o que é e como funciona. 2023. Disponível em: https://www.escoladnc.com.br/blog/crud-o-que-e-e-como-funciona. Acesso em: 4 maio 2026.

PROGRAMADORES DEPRÊ. O que é CRUD? Explicado com exemplos em PHP e MySQL. 2022. Disponível em: https://programadoresdepre.com.br/o-que-e-crud/. Acesso em: 4 maio 2026.