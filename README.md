# API Devsnotes

Uma API para gerenciar notas.

### Dependências

- **JWT Auth**: Uma biblioteca para gerenciamento de tokens de autenticação.

### Endpoints

Método | Endpoint | Parâmetros | Descrição
-|-|-|-
POST | api/auth/login | email, password | faz login
POST | api/auth/logout | - | desloga o usuário
POST | api/auth/refresh | - | renova o token de autenticação
POST | api/users | name, email, password | cria um usuário
PUT | api/users | name, email, password, password_confirmation | altera informações do usuário
GET | api/users | - | recebe os dados do usuário logado
GET | api/users/id | - | recebe os dados de um usuário
POST | api/notes | title, body, created_by, created_at, updated_at | criar nota
PUT | api/notes | title, body, created_by, created_at, updated_at | atualizar nota
GET | api/notes/id | | receber dados de uma nota
DELETE | api/notes/id | - | remover uma nota
