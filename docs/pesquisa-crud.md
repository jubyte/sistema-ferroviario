![Banner](../assets/imgs/banner_crud.png)


## 1. CONCEITO

O CRUD (Create, Read, Update e Delete) é um conjunto de quatro operações básicas utilizadas para manipular dados em sistemas de software. Esse conceito é fundamental, pois define como as informações são armazenadas, acessadas e modificadas dentro de um banco de dados ou outro tipo de repositório de dados. Segundo a Mozilla (2025), o CRUD representa as principais funções utilizadas na manipulação de informações em sistemas digitais.

O acrônimo CRUD representa quatro operações fundamentais:

- **Create (INSERT):** responsável pela inserção de novos dados no sistema, como o cadastro de usuários;
- **Read (SELECT):** utilizado para consultar ou visualizar informações armazenadas;
- **Update (UPDATE):** permite modificar dados já existentes;
- **Delete (DELETE):** realiza a remoção de dados do sistema, podendo ser feita de forma definitiva ou lógica, quando o dado apenas é marcado como inativo.

### 1.1 OPERAÇÕES

As operações CRUD são consideradas a base da maioria dos sistemas digitais, desde aplicações simples até plataformas mais complexas. Sempre que um usuário cadastra, visualiza, edita ou exclui informações, ele está utilizando alguma das operações do CRUD.

Além disso, o CRUD auxilia na organização do sistema, pois define a forma como os usuários interagem com o banco de dados. Dessa maneira, o conceito contribui para a manutenção, organização e crescimento do sistema, permitindo um gerenciamento mais eficiente das informações.

## 2. APLICAÇÃO NO PROJETO

No projeto desenvolvido, o CRUD é utilizado como base para organizar e controlar os dados recebidos pelos sensores dos trens. Cada informação coletada pelos sensores é armazenada no sistema como um dado no banco de dados.

Quando o trem envia novas informações, a operação Create é utilizada para registrar os dados no sistema. Esse processo ocorre continuamente, já que os sensores realizam coletas constantes de informações.

Por meio da operação Read, é possível visualizar os dados armazenados, como a localização do trem, a velocidade atual e o consumo de energia.

Caso alguma informação precise ser corrigida ou atualizada, utiliza-se a operação Update. Isso pode ocorrer, por exemplo, na alteração do status de um trem para indicar manutenção ou correção de dados cadastrados incorretamente.

Já a operação Delete é responsável pela remoção de informações que não são mais necessárias, como dados duplicados ou antigos. Em determinadas situações, a exclusão pode ocorrer apenas de forma lógica, mantendo os dados armazenados, porém marcados como inativos.

## REFERÊNCIAS

- MOZILLA. CRUD. MDN Web Docs, 2025. Disponível em: https://developer.mozilla.org/pt-BR/docs/Glossary/CRUD. Acesso em: 4 maio 2026.

- ESCOLA DNC. CRUD: o que é e como funciona. 2023. Disponível em: https://www.escoladnc.com.br/blog/crud-o-que-e-e-como-funciona. Acesso em: 4 maio 2026.

- PROGRAMADORES DEPRÊ. O que é CRUD? Explicado com exemplos em PHP e MySQL. 2022. Disponível em: https://programadoresdepre.com.br/o-que-e-crud/. Acesso em: 4 maio 2026.