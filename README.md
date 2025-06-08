# Laravel Docker Project Setup

## 💡 Descrição do Projeto
Este projeto configura um ambiente completo para rodar aplicações Laravel utilizando Docker, facilitando o desenvolvimento e a integração.

A aplicação utiliza o **Laravel** e conta com containers para **PHP 8.1**, **Nginx** e **MySQL 8.0**.

## 📜 Requisitos
- Docker
- Docker Compose

## 🛠️ Funcionalidades
- **Ambiente de desenvolvimento completo**: Configuração automatizada de servidor web, PHP e banco de dados.
- **Instalação automática de dependências**: O `composer install` é executado automaticamente durante o build.
- **Fácil configuração**: Permite rápida inicialização do ambiente.

## 🔧 Como Rodar o Projeto

1. **Clone o repositório**:
    ```bash
    git clone git@github.com:AndreGregoletto/appAuditoria.git
    cd docker_laravel
    ```

2. **Suba os containers com Docker Compose**:
    ```bash
    docker-compose up -d --build
    ```

3. **Verifique os containers ativos**:
    ```bash
    docker ps
    ```

   Você deve ver os containers `laravel_app`, `laravel_webserver` e `laravel_db` em execução.

4. **Acesse o container da aplicação**:
    ```bash
    docker exec -it laravel_app bash
    ```

5. **Realize ajustes finais dentro do container**:
    - Ajuste permissões:
      ```bash
      chmod -R 777 storage bootstrap/cache
      ```
    - Saia do container:
      ```bash
      exit
      ```

## 🌐 Acessando a Aplicação
1. Abra o navegador e acesse:
    ```
    http://localhost:8080
    ```
2. Você deve visualizar a página inicial do Laravel.

## 🛠️ Solução de Problemas
- **Arquivo não encontrado**: Certifique-se de que a estrutura do diretório está correta e os volumes estão sendo montados adequadamente.
- **Problemas de permissão**: Reaplique as permissões com `chmod -R 777`.
- **Reconstrução dos containers**: Se encontrar problemas, execute:
    ```bash
    docker-compose down --rmi all
    docker-compose up -d --build
    ```

## 🛑 Parando os Containers
Para parar e remover os containers, execute:
```bash
docker-compose down
