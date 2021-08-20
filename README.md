### Instruções para execucão do projeto

1. Clonar repositório
2. Criar arquivo **.env** com os dados do arquivo **.env.example**
3. No diretório do projeto executar comando **docker-compose up -d**
4. Acessar container do php e executar comando **composer install**
5. E, por fim, ainda no container do php executar comando **php artisan migrate**
