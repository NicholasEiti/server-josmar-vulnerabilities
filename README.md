<p align="center">
    <a href="https://jwt.io">
        <img
            src="https://jwt.io/img/logo-asset.svg"
            alt="Open JWT.io page for more information"
            style="width: 110px">
    </a>
    <a href="https://gitpod.io/#https://github.com/mocno/server-josmar">
        <img
            src="https://gitpod.io/button/open-in-gitpod.svg"
            alt="Open in Gitpod"
            style="width: 200px">
    </a>
</p>

# Server-Josmar e Admin-Josmar
Esse repositório é uma API (Server-Josmar) para controlar o armário "e-josmar" e um sistema de gerenciamento de usuários, chaves, etc(Admin-Josmar).  

## Rotas do Server-Josmar
### Rotas sem autenticação
- `/api/auth` - Autenticação do usuário
### Rotas com autenticação (level < 10)
- `/api/request/create` - Criar pedido de chave
- `/api/request/update_status` - Atualizar situação do pedido de chave
- `/api/user/edit` - Editar o próprio usuário
- `/api/drawer/list` - Listagem do armário

### Rotas dos "admin"
#### Rotas dos usuários
- `/api/user/create` - Criar usuário
- `/api/user/edit` - Alterar usuário
- `/api/user/list` - Listagem do usuário

#### Rotas dos pedidos
- `/api/request/list` - Listagem dos pedidos, com algumas filtragens:
    - por usuário, pelo parâmetro user_id
    - por chave, pelo parâmetro key_id
    - por data, pelo parâmetro date
#### Rotas das chaves
- `/api/key/create` - Criar chave
- `/api/key/list` - Listagem da chave
- `/api/key/remove` - Remover chave

#### Rotas dos armários
- `/api/drawer/create` - Criar armário
- `/api/drawer/edit` - Alterar armário
- `/api/drawer/remove` - Remover armário

 
