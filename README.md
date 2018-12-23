# datalayer
An attempt at building a Database Abstraction Class with PHP

The differences between database platforms can make it extremely difficult to create durable and transportable code. If the database code is tightly-knit into a project, it can be difficult to migrate from one database server to another.

DataLayer is a basic utility class that separates a lot of the database facing code from the logic of a project as a whole. The class broadly achieves two things

    1. It presents a conduit between an application and the database.
    2. It automates the generation of SQL for a range of frequently performed operations, like SELECT and UPDATE statements.

DataLayer provides two main benefits for a user of the class.

    1. In automating simple queries, it saves teh need to construct SQL statements on the fly
    2. In providing a clear interface to its functionality, it should safeguard portability.

If your project is moved from MySQL to PostgreSQL, MongoDB or any other database server, then an alternative class can be written that maintains the same functionality but with a different implementation behind the scenes.

An important item to consider is that different database engines tend to support different SQL syntax and features. SQL statements written to work with MySQL and MariaDB may not work well with others. This class keeps things simple and as 'standard' as possible, although, it can handle some pretty complex SQL statements.

The example.sql file includes the assumed sql necessary to build the example tables. The example.php file includes code showing how to use the basics of the class.

SETUP

open class.dlconfig.php located within the directory /classes/database/class.dlconfig.php. 
Configure the host, username, password, and finally, the database to match your host or local setup.

USAGE

In a live environment - It's my recommendation that class.dlconfig.php be moved OUTSIDE of the web accessible directory structure.
If your web root is /home/user/public_html - I would store this file somewhere in the /user/ directory. This keeps your database information secure if there's ever an issue with your server or the configuration is changed somehow.
