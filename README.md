# Visão Geral
O Backend foi desenvolvido com Laravel 5.5 com gerenciamento de pacotes pelo composer.
As tabelas do banco serão construídas por migrations e apenas a tabela de categoria de cursos que é populada inicialmente.
Por se tratar de uma API, a aplicação conta com um módulo para controle de CORS, atualmente está com tudo liberado.

As tabelas estão com relacionamento por chave estrangeira, porém, as ações de cascata eu deixei na inteligência do código ao invés do MySQL, preferência pessoal só.
A aplicação foi desenvolvida em Ubuntu 16, com PHP7, MySQL e Apache. Caso o ambiente seja ngynx não haverá modificação, porém, se for Lighttpd, o angular vai precisar de modificações por causa de urls.
O Laravel tem algumas exigências de módulos para o PHP (https://laravel.com/docs/5.4/#server-requirements)

O Frontend, foi desenvolvido com Angular 1.6 e com os pacotes e dependências gerenciados pelo Bower.
O layout é o AdminLTE, todo o restante foi feito em cima. Nas páginas de listas eu optei por usar um módulo (minimal-grid) que foi desenvolvido juntamente com um amigo, Saul Luz, que é para exibir a tabela, fazer ordenação e paginação. O filtro de busca foi feito fora da lista, não consta no pacote.

# Instalação

```sh
$ git clone https://github.com/biologiatotal/programador-php-pleno
```

#### Backend
Antes de tudo, precisa modificar as configurações do banco.
Abra o arquivo [backend/.env] e edite entre as linhas 8 e 13 com as informações do banco de dados.

#### Frontend
Acesse o arquivo que fica em [frontend/app/env.js] e preencha com o endereço que o backend vai estar sendo executado. O sufixo /api é obrigatório, pois é assim que foi configurado no Laravel.

Após modificar a URL no frontend e o endereço do banco no Laravel, volte ao terminal e execute o arquivo de instalação que está na raix do projeto:

```sh
$ sh install
```

Esse comando irá fazer o download dos pacotes do Laravel e do Angular, e executar as rotinas de instalação.
Para o Laravel, é fundamental que as pastas /storage e /bootstrap estejam com permissão 777, isso é executado no arquivo de instalação, porém, pode variar de acordo com as permissões de cada OS.

#### Execução

Quando a instalação terminar, basta levantar as duas aplicações:

Backend:
```sh
$ cd backend
$ php -S localhost:8091 -t public
```

Frontend:
```sh
$ cd frontend
$ http-server -a localhost -p 8090 -c-1 ./app
```

Nos exemplos acima eu usei a porta 8090 e 8091, verifique se você tem essas portas disponíveis.

#### Testes

Antes de iniciar os testes unitários, acesse o arquivo [phpunit.xml] e preencha com as informações do banco de dados de testes.

A aplicação está preparada para exectar testes unitários de todos os métodos do backend.
Foi utilizado PHPUnit para isso.
Dependendo do ambiente de cada usuário, pode modificar a forma como o PHPUnit é chamado.
No meu ambiente foi dessa forma:

```sh
$ cd backend
$ ./vendor/bin/phpunit
```

![Printscreen PHPUnit](https://kafee.com.br/biologiatotal_print_phpunit.png)