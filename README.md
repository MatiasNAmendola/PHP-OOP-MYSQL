MYSQL Class for PHP
=============

A basic PHP class using OOP to allow for easy use of popular MYSQL statements such as SELECT, UPDATE, INSERT and DELETE.

## How to Use

You must first change the variables in the db.class.php to correlate with your database.

<pre>
    private $db_host = '';
  	private $db_user = '';
    private $db_pass = '';
    private $db_name = '';
</pre>

Once you have changed these to the correct values, you must then make an instance of your class where you would like to use it.
This can be achieved as follows.

<pre>
    require ('includes/db.class.php'); // Require the class file itself.
    $db = new Database(); // Make an instance of the class.
</pre>

##### Connecting to a database

To connect to the database include the following line of code inside your project.

<pre>
    $db->connect();
</pre>

##### Disconnecting from a database

To disconnect to the database include the following line of code inside your project.

<pre>
    $db->disconnect();
</pre>

##### SELECT Query

The following code allows you to retrieve all the data from the table.

<pre>
    $db->select('TABLENAME');
</pre>

If you wish to choose specific data from the table you can modify it as follows.

<pre>
    $db->select('TABLENAME','*','A = "B"');
</pre>

##### INSERT Query

##### DELETE Query

##### UPDATE Query

##### Any SQL Query


