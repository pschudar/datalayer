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

As an example, let's pretend that you have a database called "my_users". To access it, we'll need to initialize the datalayer.

<?php
$dl = new DataLayer();
$dl->debug = false; # If true, this prints the SQL to screen. Great for error checking your code

$user = $dl->select('user_first, user_last, user_email', 'my_users', '', 'user_registered DESC');
# resolves to: SELECT user_first, user_last, user_email FROM my_users ORDER BY user_registered DESC

if(count($user) <= 0 {
echo "0 Users";
exit();
}
echo "<ul>";
foreach ($user as $d_row) {
    foreach($d_row as $field=>$val) {
        $$field = $val; # assigns table field names to stored values. $user_first = Paul, etc.
    }
    echo "<li>$user_first</li>
    <li>$user_last</li>
    <li>$user_email</li>";
}
echo "</ul>";

More to come soon.
    
