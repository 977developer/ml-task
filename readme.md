## Introduction

Run the following commands to build.
```
docker compose build
docker compose up
```

Make PHP api work :

```
docker compose exec api composer install
```
Api will be served at : http://localhost:9000/subscribers

To make angular app work
```
cd app
npm install
npm start
```
It will be served at http://localhost:4200

Database should already be synced on docker build. However, if needed, the SQL file is inside :
```
docker/mysql/db.sql
```
## API Routes

```
GET : http://localhost:9000/subscribers
GET : http://localhost:9000/subscribers/{emailId}

POST : http://localhost:9000/subscribers
BODY :
{
  "firstName": "hello",
  "lastName": "world",
  "email": "hello@example.com",
  "status": 1
}

```

## Folder structure

This task is divided into 3 folders.
```
**api** - Contains PHP code
**app** - Contains angular code.
**docker** - Contains docker configurations.
```

## PHP Section

This is a simple implementation for the task. It follows PSR-2 coding standards. I have also made sure that it is setup with PHPCS (https://github.com/squizlabs/PHP_CodeSniffer).

```
docker compose exec api sniff
docker compose exec api fix
```

To test the application :
```
docker compose exec api composer test
```
## Redis

It implements Redis as primary database. Data is always fetched from Redis, if it is not found, it is queried to MySQL and saved to Redis.

Configuration :
```
REDIS_PORT: 6379

Can be connected using 127.0.0.1:6379
```

Read Operations :
```
	=> Does data exist in Redis ?
	=> Yes ? Return data from Redis
	=> No ? Fetch from MySql, Save into Redis, Return Data
	=> Next time, deliver from redis
```

For write operations:
```
	=> Write into Redis
	=> Send Response To User
	=> Write to MYSQL in the background
```
## MySQL

It implements MySQL as a secondary database. Can be accessed externally by :
```
Host : 127.0.0.1
Port : 3307
username: root
password: root
```

And that is it.

Thank you for visiting. Also have a look at this document for the answers regarding these tasks.
https://docs.google.com/document/d/1CXqgyXbxhXcyrD8-8G_6HcMj0erh_TtN-z_fpMMP5GU/edit?usp=sharing 
