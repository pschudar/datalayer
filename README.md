# datalayer
An attempt at database abstraction with PHP

I originally found a similar snippet of code buried in a copy of Sam's Teach Yourself PHP in 24 hours back in the early 2000's.
It was meant to be used as a vehicle to learning OOP with PHP 4.x, though it's been modified by me to be used with PHP 5 and now, 7.

As the original code is now obsolete, and the modifications I have applied are extensive, I consider this code to be a completely
separate work, which is why it's now licensed under the GPL. I do not believe the original code was licensed - it was public domain
from my understanding.

First, open class.dlConfig.php and configure with your database host, username, password, and finally, the name of your database.
That's it. Pretty simple stuff.

I should note here that class.dlConfig.php was not originally included with Matt Zandstra's work. It's something I wrote while learning
to code and use an interface.

That being said, include class.dlConfig.php and class.datalayer.php into your project. You can now use the datalayer class to write 
MySQL queries with ease.

As an example, let's pretend that you have a database called "datalayer". To access it, we'll need to initialize the DataLayer. 

Please view the example.sql file for the assumed sql (generated with phpMyAdmin) and example.php page to see how this code could be used
in a development or production environment.

As always, I am open to constructive criticism. 
