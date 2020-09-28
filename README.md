# API Devsnotes

Uma API para gerenciar notas.

### Endereço
#### [https://apidevsnotes.herokuapp.com/](https://apidevsnotes.herokuapp.com/)

### Endpoints

Método | Endpoint | Parâmetros | Descrição
-|-|-|-
POST | api/auth/login | email, password | fazer login.
POST | api/auth/logout | - | finalizar sessão.
POST | api/auth/refresh | - | renovar token de autenticação.
GET | api/users | - | listar usuários.
GET | api/user | - | receber dados do usuário logado.
GET | api/user/:id | - | receber dados de um usuário.
POST | api/user | name, email, password, password_confirmation | criar usuário.
PUT | api/user | name, email, password, password_confirmation | alterar informações do usuário logado.
GET | api/notes | - | listar notas.
GET | api/note/:id | - | receber dados de uma nota.
POST | api/note | title, body | criar nota.
PUT | api/note | title, body | atualizar nota.
DELETE | api/note/:id | - | remover uma nota.


### Dependências

- [**JWT Auth**](https://jwt-auth.readthedocs.io/en/develop/): Uma biblioteca para gerenciamento de tokens de autenticação.
- [**Laravel pt_BR Localization**](https://github.com/lucascudo/laravel-pt-BR-localization): Um módulo de linguagem Português do Brasil para Laravel.

### Instalação

1. instale as dependências do projeto executando o comando:
```shell
$ composer install
```

2. na pasta do projeto, crie uma cópia do arquivo <b>.env.example</b> e o renomeie para <b>.env</b>:
```shell
$ cp .env.example .env
```

3. abra o arquivo .env e altere as seguintes linhas, de acordo com a conexão do seu banco de dados:
```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

4. rode o comando abaixo para gerar uma nova chave para o projeto, a qual será salva no arquivo .env:
```shell
$ php artisan key:generate
```

5. execute as migrations:
```shell
$ php artisan migrate
```

6. publique as configurações do JWTAuth com o seguinte comando:
```shell
$ php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
```

7. gere uma ```secret key``` para o JWTAuth:
```shell
$ php artisan jwt:secret
```

8. finalmente, rode o servidor com o comando:
```shell
$ php artisan serve
```