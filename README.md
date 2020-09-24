# API Devsnotes

Uma API para gerenciar notas.

### Dependências

- **JWT Auth**: Uma biblioteca para gerenciamento de tokens de autenticação.

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
