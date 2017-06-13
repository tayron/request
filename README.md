## Request

Classe que trata e gerencia informações de requisições


## Recursos
  - redirect($controllerAction) - Rerediona para a uri informada, exemplo: cliente/listar
  - getUri() - Método que retorna uma lista de parametros informados na URI
  - getPostParameter($key) - Metodo que retorna os parametros enviados via POST
  - getGetParameter($key) - Metodo que retorna os parametros enviados via GET 
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
