**Project Fridge**

Symfony 4.0 project with use GraphQL 

# Installation

1) `git clone git@github.com:smartynovych/fridge4.git`

2) `composer install`

3) `php bin/console do:da:cr`

4) `php bin/console do:sc:up --force`

5) `php bin/console do:fi:lo -n`

6) 
run server: `php -S 127.0.0.1:8080 -t public`

or use Docker: `docker-compose up`

# Using
GraphQL endpoint:
  http://127.0.0.1:8080/api/
  
GraphQL Explorer:
  http://127.0.0.1:8080/api/graphiql
  
# Examples
  - View name of all products:
  http://127.0.0.1:8080/api/graphql/fridge?query=query{viewAll{view{name}}}
  
  - Add new product: 
  http://127.0.0.1:8080/api/graphql/fridge?query=mutation{create(input:{name:"CocaCola",description:"Refreshing Drink",volume:1.5,expirationDate:"01.06.2018",section:Door}){id}}
  