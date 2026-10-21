![Banner](../assets/imgs/banner_apis.png)

# APIs

## 1. CONCEITO

API (*Application Programming Interface*, ou Interface de Programação de Aplicações) é um conjunto de regras que permite a comunicação entre sistemas. Ela possibilita que uma aplicação solicite dados ou funcionalidades de outra sem precisar conhecer todos os detalhes de seu funcionamento.

## 2. FUNCIONAMENTO E FINALIDADE

A comunicação geralmente envolve três elementos: o cliente, que faz a solicitação; a API, que recebe e processa a requisição; e o servidor, que retorna uma resposta.

Por exemplo, no Mocking Rail, uma aplicação poderia solicitar à API a lista de trens cadastrados e receber os dados para exibição.

## 3. APIs REST

REST é um estilo de arquitetura utilizado para desenvolver APIs que trabalham com recursos identificados por URLs e utilizam métodos HTTP para realizar operações.

Os principais métodos são:

| Método | Finalidade |
|---|---|
| `GET` | Consultar dados |
| `POST` | Cadastrar dados |
| `PUT` | Substituir ou atualizar um recurso |
| `PATCH` | Atualizar parte de um recurso |
| `DELETE` | Excluir um recurso |

## 4. REQUISIÇÕES E RESPOSTAS

Uma requisição é uma solicitação enviada à API. Ela pode conter um método HTTP, um endereço, cabeçalhos e, quando necessário, dados enviados no corpo da mensagem.

O **endpoint** é o endereço utilizado para acessar determinado recurso da API. Em um exemplo hipotético do Mocking Rail, poderiam existir:

- `/api/trens` — acessar os trens;
- `/api/sensores` — acessar os sensores;
- `/api/rotas` — acessar as rotas.

Após processar a requisição, a API retorna uma resposta com os dados solicitados ou uma mensagem sobre o resultado. Alguns códigos HTTP comuns são:

| Código | Significado |
|---|---|
| `200` | Requisição realizada com sucesso |
| `201` | Recurso criado |
| `400` | Requisição inválida |
| `401` | Autenticação necessária ou inválida |
| `403` | Acesso não permitido |
| `404` | Recurso não encontrado |
| `500` | Erro interno do servidor |

## 5. JSON

JSON (*JavaScript Object Notation*) é um formato de texto utilizado para armazenar e trocar dados entre sistemas. Ele organiza as informações em pares de chave e valor, permitindo que diferentes linguagens de programação interpretem os mesmos dados.

Exemplo de resposta JSON com dados fictícios:

```json
{
  "id": 1,
  "nome": "Distrito 12",
  "tipo": "Eletrico",
  "status": "Ativo"
}
```

Nesse exemplo, cada chave identifica uma informação do trem, acompanhada de seu respectivo valor.

## 6. APIs E SISTEMA WEB

Um sistema web tradicional geralmente processa uma solicitação e retorna uma página HTML pronta para ser exibida no navegador. Já uma API disponibiliza dados ou funcionalidades para que outra aplicação os utilize, muitas vezes em formato JSON.

As duas abordagens podem trabalhar juntas: um sistema web pode utilizar uma API para consultar informações e apresentá-las em sua própria interface.

## 7. APIs EM PHP

O PHP permite tanto consumir APIs externas quanto criar APIs próprias. Para isso, pode utilizar cURL para realizar requisições HTTP, `json_decode()` para interpretar JSON recebido e `json_encode()` para converter dados PHP em JSON.

### CONSUMO
O exemplo abaixo demonstra uma requisição GET utilizando cURL. O endereço é ilustrativo e deve ser substituído pelo endpoint de uma API existente para realizar um teste real.

```php
<?php
$url = "https://exemplo.com/api/trens";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$resposta = curl_exec($curl);
$erro = curl_errno($curl);

curl_close($curl);

if ($erro) {
    die("Erro ao consultar a API.");
}

$dados = json_decode($resposta, true);

print_r($dados);
?>
```

O cURL realiza a requisição e recebe a resposta. Em seguida, `json_decode()` converte o JSON em uma estrutura que pode ser utilizada pelo PHP.

### DISPONIBILIZAÇÃO

O código a seguir demonstra como retornar dados em JSON a partir do PHP:

```php
<?php
header("Content-Type: application/json; charset=utf-8");

$trens = [
    ["id" => 1, "nome" => "Expresso Distrito 12"],
    ["id" => 2, "nome" => "Trem de Carga 02"]
];

echo json_encode($trens, JSON_UNESCAPED_UNICODE);
?>
```

O cabeçalho informa que a resposta está em JSON, e `json_encode()` converte o array PHP para esse formato. Em uma aplicação completa, os dados poderiam ser consultados no banco de dados e disponibilizados por um endpoint configurado no servidor.

Os dois códigos são exemplos didáticos e não representam uma API já implementada no Mocking Rail.

## 8. SEGURANÇA

A segurança de uma API exige medidas para proteger os dados e controlar o acesso aos recursos. Entre as principais estão:

- **Autenticação:** verifica a identidade de quem acessa o sistema.
- **Autorização:** define quais recursos e operações cada usuário pode acessar.
- **HTTPS:** protege a comunicação entre cliente e servidor durante a transmissão.
- **Validação:** verifica os dados recebidos antes de processá-los.
- **Controle de acesso:** impede que usuários realizem operações sem permissão.

Essas medidas ajudam a reduzir acessos indevidos, exposição de informações e outras vulnerabilidades.

## REFERÊNCIAS

MDN WEB DOCS. **Introdução às Web APIs**. Disponível em: https://developer.mozilla.org/pt-BR/docs/Learn_web_development/Extensions/Client-side_APIs/Introduction. Acesso em: 20 out. 2026.

MDN WEB DOCS. **Métodos de requisição HTTP**. Disponível em: https://developer.mozilla.org/pt-BR/docs/Web/HTTP/Reference/Methods. Acesso em: 20 out. 2026.

MDN WEB DOCS. **Códigos de status de respostas HTTP**. Disponível em: https://developer.mozilla.org/pt-BR/docs/Web/HTTP/Reference/Status. Acesso em: 20 out. 2026.

PHP. **Biblioteca Cliente URL (cURL)**. Disponível em: https://www.php.net/manual/pt_BR/book.curl.php. Acesso em: 20 out. 2026.

PHP. **json_decode**. Disponível em: https://www.php.net/manual/pt_BR/function.json-decode.php. Acesso em: 20 out. 2026.

PHP. **json_encode**. Disponível em: https://www.php.net/manual/pt_BR/function.json-encode.php. Acesso em: 20 out. 2026.
