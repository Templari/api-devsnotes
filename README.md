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
POST | api/users | name, email, password, password_confirmation | criar usuário.
PUT | api/users | name, email, password, password_confirmation | alterar informações de usuário.
GET | api/users | - | listar usuários.
GET | api/user | - | receber dados do usuário logado.
GET | api/user/:id | - | receber dados de um usuário.
DELETE | api/user/:id | - | remover um usuário.
POST | api/notes | title, body, created_by | criar nota.
PUT | api/notes | title, body, created_by | atualizar nota.
GET | api/notes/:id | - | receber dados de uma nota.
DELETE | api/notes/:id | - | remover uma nota.
