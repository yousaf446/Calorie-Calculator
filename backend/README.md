# Calories tracking app backend

## Rest based APIs authenticated via JWT token

## Pre-requisites to install
### MySQL
### PHP
### composer

## App Configuration

### Install required libraries
Go to project directory and run following command

composer install

### Database
Create database inside mysql using following command

CREATE DATABASE calories_app

Update MySQL credentials (username and password) inside .env file

Run following command for migrating database tables

php artisan migrate

### Testing
For running API unit tests run following command

php artisan test

### Database dump
Import sample data inside created mysql database from mysql_dumps folder



### Run backend server
Use following command for running backend server

php artisan serve

Go to http://localhost:8000
