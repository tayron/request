## Request

Classe que trata e gerencia informações de requisições, usando biblioteca 
symfony/http-foundation na versão: v2.8.22


## Recursos
  - redirect($location) - Rerediona para a uri informada, exemplo: cliente/listar ou http://www.google.com.br
  - getUri() - Método que retorna uma lista de parametros informados na URI
  - getPostParameter($key) - Metodo que retorna os parametros enviados via POST
  - getGetParameter($key) - Metodo que retorna os parametros enviados via GET 
  - getPutParameter($key) - Metodo que retorna os parametros enviados via PUT
  - url($controllerAction) - Método que monta a retorna url para uma determinada pagina
  - isPost() - Método que informa se a requisição é via POST
  - isGet() - Método que informa se a requisição é via GET
  - isPut() - Método que informa se a requisição é via PUT
  - isDelete() - Método que informa se a requisição é via DELETE
   


## Utilização via composer

```sh
    "require": {
        ...
        "tayron/request" : "1.0.0"
        ... 
    },    
```
