<?php
# Include DataLayer config and class
require_once('class.dlconfig.php');
require_once('class.datalayer.php');
# Initialize the DataLayer class
$dl = new DataLayer();
$dl->debug = true; # If true, this prints the SQL to screen. Great for error checking your code

echo '<h4>Simple Example of pulling all data from the the users table</h4>';

# Select all info from users
$users = $dl->select('user_name, user_email, user_age', 'users', '', 'id DESC');

# loop through the result set
foreach($users as $d_row) { 
    foreach($d_row as $field=>$val) { 
        $$field = $val; # assigns table field names to stored values
    }
    echo '<strong>Name:</strong> ' . $user_name . '<br> <strong>Email:</strong>' . $user_email . '<br> <strong>Age:</strong>' . $user_age . '<br><br>';
}

echo '<h4>Simple example of pulling specific types of users with an age of less than 18</h4>';

$example_users = $dl->select('user_name, user_email, user_age', 'users', 'user_age<18', '', '');

# loop through the result set
foreach($example_users as $d_row) { 
    foreach($d_row as $field=>$val) { 
        $$field = $val; # assigns table field names to stored values
    }
    echo '<strong>Name:</strong> ' . $user_name . '<br> <strong>Email:</strong>' . $user_email . '<br> <strong>Age:</strong>' . $user_age . '<br><br>';
}

echo '<h4>An example of pulling data from two tables in one query for one specific user (such as a blog post or the like)</h4>';

$example_data = $dl->select('a.id, a.content_title, a.content_body, b.user_name, b.user_email, b.user_age', 'user_content a, users b', 'a.user_id = 3 && b.id = 3');

# loop through the result set
foreach($example_data as $d_row) { 
    foreach($d_row as $field=>$val) { 
        $$field = $val; # assigns table field names to stored values
    }
    echo '<strong>Post Title: </strong> ' . $content_title . '<br> <strong>Content Body: </strong>' . $content_body . '<br> <strong>Author: </strong>' . $user_name . '<br><strong>Author Age: </strong>' . $user_age . '<br><strong>Author Email: </strong>' . $user_email . '<br><br>';
}

echo '<h4>An example of an insert</h4>';

$new_user = ['user_name' => 'John', 'user_email' => 'john@host.net', 'user_age' => 31];

$insert_user = $dl->insert('users', $new_user);

echo '<h4>Lets re-loop through the users once more so you can see the new addition</h4>';

$users_2 = $dl->select('user_name, user_email, user_age', 'users', '', 'user_age ASC');

# loop through the result set
foreach($users_2 as $d_row) { 
    foreach($d_row as $field=>$val) { 
        $$field = $val; # assigns table field names to stored values
    }
    echo '<strong>Name:</strong> ' . $user_name . '<br> <strong>Email:</strong>' . $user_email . '<br> <strong>Age:</strong>' . $user_age . '<br><br>';
}

echo '<h4>An example of an update</h4>';

$updated_user = ['user_name' => 'Johnny', 'user_email' => 'johnny@host.net', 'user_age' => 21];

$update_user = $dl->update('users', $updated_user, 'user_name = "John"');
echo '<h4>Pull the one user we updated to see the outcome</h4>';
$user_updated = ['user_name' => 'Johnny'];
$newly_updated_user = $dl->select('user_name, user_email, user_age', 'users', $user_updated);

# loop through the result set
foreach($newly_updated_user as $d_row) { 
    foreach($d_row as $field=>$val) { 
        $$field = $val; # assigns table field names to stored values
    }
    echo '<strong>Name:</strong> ' . $user_name . '<br> <strong>Email:</strong>' . $user_email . '<br> <strong>Age:</strong>' . $user_age . '<br><br>';
}

echo '<h4>An example of deleting a user</h4>';

$delete = $dl->delete('users', $user_updated);

echo '<h4>Pull all current users to see that the newly added and updated user has been deleted</h4>';

$all_users = $dl->select('user_name, user_email, user_age', 'users', '', 'id DESC');

# loop through the result set
foreach($all_users as $d_row) { 
    foreach($d_row as $field=>$val) { 
        $$field = $val; # assigns table field names to stored values
    }
    echo '<strong>Name:</strong> ' . $user_name . '<br> <strong>Email:</strong>' . $user_email . '<br> <strong>Age:</strong>' . $user_age . '<br><br>';
}
