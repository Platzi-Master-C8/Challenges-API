# Challenges API 

This project provide a lot of tools that can help you while creating a code challenge webstite! 

## Technologies
- [Laravel](https://laravel.com/)
- [Docker](https://www.docker.com/)
- [Postgresql](https://www.postgresql.org/) or any sql engine


## Installation
#### Copy and configure env 
`cp .env.example .env`


#### If you want to use a temporal postgresql db, you can use docker compose in order to mount a special db for the project and avoid using local db 

`sudo systemctl stop postgresql`

`docker-compose up -d`

Docker is required no matter wheter you use docker compose for your database, it will be used within the project to run code.


#### Finally set the following env variables: 

`DB_CONNECTION=`

`DB_HOST=`

`DB_PORT=`

`DB_DATABASE=`

`DB_SCHEMA=`

`DB_USERNAME=`

`DB_PASSWORD=`



#### Api docs

[Postman documentation](https://www.postman.com/crimson-meteor-760691/workspace/platzi-challenges/documentation/16601526-88e5c1af-eb1e-4cac-ac4c-4dc0ea19e773)

