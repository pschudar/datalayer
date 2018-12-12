# datalayer
An attempt at database abstraction with PHP

I originally found this little snippet of code buried in a copy of Sam's Teach Yourself PHP in 24 hours back in the early 2000's.
It was meant to be used as a vehicle to learning OOP with PHP 4.x, though it's been modified by me to be used with PHP 5 and now, 7.

There are two files to include in a project in order to use dataLayer. class.dlConfig.php and datalayer.php

First, open class.dlConfig.php and configure with your database host, username, password, and finally, the name of your database.
That's it. Pretty simple stuff.

I should note here that class.dlConfig.php was not originally included with Matt Zandstra's work. It's something I wrote while learning
to code and use an interface.

That being said, include class.dlConfig.php and class.datalayer.php into your project. You can now use the datalayer class to write 
MySQL queries with ease.

As an example, let's pretend that you have a database called "datalayer". To access it, we'll need to initialize the datalayer. 

Please view the example.php page to see this script in action.
    
