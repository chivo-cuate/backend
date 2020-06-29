### Chivo Cuate - Backend Server
INSTALLATION INSTRUCTIONS:
-------------------
- Create a new file in `config\db.php` with the following contents (adjust values):
  ```php
  return [
      'class' => 'yii\db\Connection',
      'dsn' => 'mysql:host=localhost;dbname=db_name',
      'username' => 'user',
      'password' => 'password',
      'charset' => 'utf8',
  ];
  ```
- Make sure the `app\web\assets` directory is writable by the Web server process.
- Start your desired Web server or docker container.
~~~
php -S 127.0.0.1:8082 --docroot=web/
#or
docker-compose up
~~~

Now you should be able to access the application through the following URL, assuming your Web server is running on port `8080`:

~~~
http://localhost:8080/
~~~
