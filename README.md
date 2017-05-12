# Many to Many

#### Epicodus PHP Week 4 Code Review, 3/3/2017

#### By Benjamin T. Seaver

## Description

Demonstrate PHP Objects, automated testing, CRUD methods, RESTful routing, many to many relationships and an MVC style website using Silex with Twig views.

## Project Requirements:
Write a program to list out local shoe stores and the brands of shoes they carry. Make a `Store` class and a `Brand` class.

* Create a database called `shoes` and a testing database called `shoes_test`, and remember to follow proper naming conventions for your tables and columns. As you create your tables, _copy all MySQL commands into your README_.

* Build full CRUD functionality for Stores. Create, Read (all and singular), Update, Delete (all and singular).

* Allow a user to create Brands that are assigned to a Store. Don't worry about updating or deleting Brands.

* There is a many-to-many relationship between Brands and shoe Stores. Many Stores can carry many Brands and a Brand can be carried in many Stores. _Create a join table to store these relationships._

* When a user is viewing a single Store, list all Brands that Store currently carries, and allow them to add another Brand to that store. _Create a method to get the Brands sold at a Store, and use a join statement in it._

* When a user is viewing a single Brand, list all Stores that carry that Brand and allow them to add a Store to that Brand. Use a join statement in this method too.

* When you are finished with the assignment, make sure to _export your databases and commit the .sql.zip files for both the app database and the test database_ at the top of your project directory.

## Setup Requirements
* See https://secure.php.net/ for details on installing _PHP_.  Note: PHP is typically already installed on Mac.
* See https://getcomposer.org/ for details on installing _composer_.
* See https://mamp.info/ for details on installing _MAMP_.

## Installation Instructions
* Clone project.
* From project root, run $ `composer install --prefer-source --no-interaction`
* Assumes Mamp MySQL user and password of `root`
* Start MAMP servers.
* Use MAMP website `http://localhost:8888/phpmyadmin/` to import database with sample data from the `shoes.sql.zip` file.
* To enable the PHPUnit Tests, use MAMP website to import the `shoes_test.sql.zip` database.
* To run PHPUnit tests from project root, run $ `vendor/bin/phpunit tests`
* To run website using installed _PHP_ (better error messages):
    * From `web` folder in project, run $ `php -S localhost:8000`.
    * In web browser open `localhost:8000`.
* To run website using _MAMP_:
    * Change the document root to the project `web` folder.
    * In web browser open `localhost:8888`.
* To start interactive SQL at command prompt run $ `/Applications/MAMP/Library/bin/mysql --host=localhost -uroot -proot`

## Known Bugs
* No known bugs

## Support and contact details
* No support

## Technologies Used
* PHP
* MAMP
* mySQL
* Composer
* PHPUnit
* Silex
* Twig
* Bootstrap
* HTML
* CSS
* Git

## Copyright (c)
* 2017 Benjamin T. Seaver

## License
* MIT

## Implementation Plan

* In interactive SQL, create database and tables (you can paste all lines below at once at the `mysql>` prompt):

CREATE DATABASE IF NOT EXISTS `shoes`;

USE `shoes`;

DROP TABLE IF EXISTS `stores`;

CREATE TABLE stores (id SERIAL PRIMARY KEY, name varchar(255));

DROP TABLE IF EXISTS `brands`;

CREATE TABLE brands (id SERIAL PRIMARY KEY, name varchar(255));

DROP TABLE IF EXISTS `brands_stores`;

CREATE TABLE brands_stores (id SERIAL PRIMARY KEY, brand_id BIGINT, store_id BIGINT);


* Install dependencies (composer.json, composer.lock, .gitignore)
* Build and test BrandStore join class (src/BrandStore.php, tests/BrandStoreTest.php)
* Build and test Brand class (src/Brand.php, tests/BrandTest.php)
* Build and test Store class (src/Store.php, tests/StoreTest.php)
* Create Silex framework (web/index.php, app/app.php)
* Create empty routes for all CRUD methods
* Design pages to demonstrate many to many relationships between Stores and Brands and also provide Add, Update, Display and Delete features.
* Implement routes with object operations and views

End specifications
