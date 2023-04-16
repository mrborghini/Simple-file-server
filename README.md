# Simple-file-server

This is a simple cloud site that you can setup as a NAS and access remotely.

# How do I set it up?

1. First install any version of MySQL and Apache and PHP 8.x.x. Although things on PHP 7.x.x might work, maybe in the future I'll use more modern functions and features.

2. Then import the ```database/import.sql``` file into MySQL.

3. After that you can setup a user with a secure password and all privileges.

4. Then you put all the files in your server root. On windows it's usually the ```HTDOCS``` directory (XAMPP). On Debian: ```/var/www/html/```.

5. Edit ```database/dbconnect.php_example``` and name it ```dbconnect.php``` and add your credentials there.

6. Hash your password using passwordhasher.php. (Just run it in the command line).

7. Add users by using the following query in MySQL: ```INSERT INTO users (email, password) VALUES ('email@example.com', 'HashedPassword');```.
The HashedPassword should look something like this: ```$2y$10$8OEVG0m8mRDKf.l0oXe7EuwARryriOReeqXKNQQIOaHLdKmTC0012```.

Congratulations now you have your own cloud!


# Troubleshooting

On some Linux distrobutions you might need to setup so it can read .htaccess.