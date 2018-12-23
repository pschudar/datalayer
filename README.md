# datalayer
An attempt at building a Database Abstraction Class with PHP

The differences between database platforms can make it extremely difficult to create durable and transportable code. If the database code is tightly-knit into a project, it can be difficult to migrate from one database server to another.

DataLayer is a basic utility class that separates a lot of the database facing code from the logic of a project as a whole. The class broadly achieves two things

    1. It presents a conduit between an application and the database.
    2. It automates the generation of SQL for a range of frequently performed
       operations, such as SELECT and UPDATE statements.

DataLayer provides two main benefits for a user of the class.

    1. In automating simple queries, it saves the need to construct SQL statements on the fly
    2. In providing a clear interface to its functionality, it should safeguard portability.

If your project is moved from MySQL to PostgreSQL, MongoDB or any other database server, then an alternative class can be written that maintains the same functionality but with a different implementation behind the scenes.

An important item to consider is that different database engines tend to support different SQL syntax and features. SQL statements written to work with MySQL and MariaDB may not work well with other platforms. For simple projects, you can deal with this problem by keeping things as 'standard' as possible.

How to use the DataLayer class:

-- example.sql

-- example.php

example.sql contains SQL code to  build the example tables we're working with in example.php. Run in phpMyAdmin.
example.php contains PHP code to demonstrate how to use the DataLayer API

Configure

open class.dlconfig.php located within the directory /classes/database/class.dlconfig.php. 
Configure the host, username, password, and finally, the database to match your host or local setup.

You may have noticed that dlConfig is actually a trait. It's named class.dlconfig.php in this example so 
it's automatically loaded by the included Autoloader class. Within the trait, a few abstract classes are defined. 
If a new class is created for a different database server, these classes must be fleshed out to keep the API the same.

USAGE

In a live environment - It's my recommendation that class.dlconfig.php be moved OUTSIDE of the web accessible directory structure.
If your web root is /home/user/public_html - I would store this file somewhere in the /user/ directory. This keeps your database information secure if there's ever an issue with your server or the configuration is changed somehow.

Further Examples

I'll be adding more examples in the future showing how to use DataLayer with more complex SQL queries (INNER JOIN, LEFT JOIN, etc).
