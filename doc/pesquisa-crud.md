![Banner](../assets/imgs/Banner_crud.jpg)

CAPÍTULO 1 - CONCEITO 
CRUD (Criar, Ler, Atualizar e Excluir) é um conjunto de quatro operações básicas usadas para manipular dados em sistemas de software. Esse conceito é fundamental porque define como as informações são armazenadas, acessadas e modificadas dentro de um banco de dados ou qualquer outro tipo de repositório de dados.
Cada letra do acrônimo representa uma ação essencial:
Create (INSERT): inserir novos dados no sistema, como cadastrar um usuário.
 Read (SELECT): consultar ou visualizar informações já armazenadas.
 Update (UPDATE): modificar dados existentes, como editar um cadastro.
 Delete (DELETE): remover dados, que pode ser feito de forma definitiva ou apenas lógica (quando o dado não é apagado, mas marcado como inativo).

CAPÍTULO 1.1 - OPERAÇÕES
Essas operações são a base de praticamente todos os sistemas digitais, desde aplicativos simples até plataformas mais complexas. Sempre que o usuário cadastra, visualiza, edita ou exclui informações, ele está usando o CRUD. Além disso, o CRUD ajuda a organizar o sistema, pois define como o usuário se comunica com o banco de dados. Por isso, é considerado essencial no desenvolvimento, garantindo organização, facilidade de manutenção e crescimento do sistema. No fim, ele permite que o usuário gerencie os dados de forma completa e simples dentro da aplicação.

CAPÍTULO 2 - APLICAÇÃO NO PROJETO 
No nosso projeto o CRUD é a base para organizar e controlar os dados que vêm dos sensores dos trens. Basicamente, cada informação coletada vira um dado dentro do sistema. Quando o trem enviar novos dados, o sistema usa o “Create” para salvar essas informações no banco de dados. Isso acontece o tempo todo, já que os sensores estão sempre coletando dados. Depois, com o “Read”, você consegue visualizar essas informações. Por exemplo, dá para ver onde o trem está, qual é a velocidade atual ou quanto de energia ele está consumindo.
Se algum dado precisa ser corrigido ou atualizado, entra o “Update”. Isso pode acontecer se houver algum erro ou se você quiser mudar o status de um trem, como indicar que ele está em manutenção. Já o “Delete” serve para remover dados que não são mais úteis, como informações duplicadas ou muito antigas. Em alguns casos, ao invés de apagar de vez, você pode apenas marcar o dado como desativado.

Referências: 

MOZILLA. CRUD. MDN Web Docs, 2025. Disponível em: https://developer.mozilla.org/pt-BR/docs/Glossary/CRUD. Acesso em: 4 maio 2026.

ESCOLA DNC. CRUD: o que é e como funciona. 2023. Disponível em: https://www.escoladnc.com.br/blog/crud-o-que-e-e-como-funciona. Acesso em: 4 maio 2026.

PROGRAMADORES DEPRÊ. O que é CRUD? Explicado com exemplos em PHP e MySQL. 2022. Disponível em: https://programadoresdepre.com.br/o-que-e-crud/. Acesso em: 4 maio 2026.