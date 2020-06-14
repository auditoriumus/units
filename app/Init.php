<?php


class Init
{
    protected $pdo;

    public function __construct()
    {
        $dsn = 'mysql:host=localhost;dbname=units;charset=utf8';
        $pdo = new PDO($dsn, 'root', 'root');
        $this->pdo = $pdo;
    }

    /**
     * Метод добавляет нового пользователя
     */
    public function addUser() {
        $values = [
            'email' => trim($_POST['email']),
            'password' => password_hash($_POST['password'], PASSWORD_BCRYPT)
        ];
        $sql = 'INSERT INTO users (email, hashpwd) VALUES (:email, :password)';
        $query = $this->pdo->prepare($sql);
        $query->execute($values);
    }

    /**
     * Метод добавляет IP пользователя
     */
    public function addIp() {
        $values = [
            'ip' => $_SERVER['SERVER_ADDR'],
            'entry_time' => time()
        ];
        $sql = 'INSERT INTO users_ip (ip, entry_time) VALUES (:ip, :entry_time)';
        $query = $this->pdo->prepare($sql);
        $query->execute($values);
    }

    /**
     * Метод обновляет время регистрации по IP пользователя
     */
    public function updateIp($ip) {
        $values = [
            'ip' => $ip,
            'entry_time' => time()
        ];
        $sql = 'UPDATE users_ip SET entry_time = :entry_time WHERE ip = :ip';
        $query = $this->pdo->prepare($sql);
        $query->execute($values);
    }

    /**
     * Метод находит время последней попытки зарегистрироваться по IP пользователя
     */
    public function findTimeByIp($ip) {
        $values = [ 'ip' => $ip ];
        $sql = 'SELECT entry_time FROM users_ip WHERE ip = :ip';
        $query = $this->pdo->prepare($sql);
        $query->execute($values);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Метод осуществляет проверку, введенных данных пользователем при регистрации
     * перед добавдением нового пользователя
     */
    public function newUserValidate() {

        $entry_time = $this->findTimeByIp($_SERVER['SERVER_ADDR']);
        if (!empty($entry_time)) {
            if (time() - $entry_time[0]['entry_time'] < 20) {
                $result['email'] = 'Исчерпан лимит по времени. Пожалуйстa, зарегистрируйтесь позже';
                $this->updateIp($_SERVER['REMOTE_ADDR']);
                return $result;
            } else {
                $this->updateIp($_SERVER['REMOTE_ADDR']);
            }
        } else {
            $this->addIp();
        }


        $email = $_POST['email'];
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];

        $result = [];
        if (!$email) $result['email'] = 'Поле email обязательно для заполнения';
        if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) $result['email'] = 'Неверно указан email';
        if (!$password) $result['password'] = 'Поле пароль обязательно для заполнения';
        if (!$password_confirm) $result['password_confirm'] = 'Введите подтверждение пароля';
        if ($password !== $password_confirm) $result['password_confirm'] = 'Пароли не совпадают';

        $values = ['email' => $email];
        $sql = "SELECT id FROM users WHERE email = :email";
        $query = $this->pdo->prepare($sql);
        $query->execute($values);
        $id = $query->fetch();
        if($id != null) $result['email'] = 'Пользователь с таким email уже существует';

        if(empty($result)) {
            $this->addUser();
            $result['success'] = 'Регистрация прошла успешно, теперь можете войти';
        }
        return $result;
    }

    /**
     * Метод осуществляет аутентификацию пользователя
     */
    public function login() {

        $email = $_POST['email'];
        $password = $_POST['password'];

        $result = [];
        if (!$email) {
            $result['email'] = 'Поле email обязательно для заполнения';
        } elseif ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result['email'] = 'Неверно указан email';
        }
        if (!$password) $result['password'] = 'Поле пароль обязательно для заполнения';

        if (!empty($result)) return $result;

        $values = ['email' => $email];
        $sql = "SELECT hashpwd, attempts FROM users WHERE email = :email";
        $query = $this->pdo->prepare($sql);
        $query->execute($values);
        $hash = $query->fetch(PDO::FETCH_ASSOC);


        if ($hash == null) {
            $result['email'] = 'Пользователя с таким email не существует';
            return $result;
        }

        //Проверка на подбор паролей
        if ($hash['attempts'] >= 5) {
            $result['email'] = 'Email заблокирован, обратитесь к администратору.';
            return $result;
        }

        if (password_verify($password, $hash['hashpwd'])) {
            session_start();
            $_SESSION['email'] = $email;
            $values = [
                'email' => $email
            ];
            $sql_attempts = "UPDATE users SET attempts = 0 WHERE email = :email";
            $query = $this->pdo->prepare($sql_attempts);
            $query->execute($values);
            $result['success'] = 'Добро пожаловать!';
        } else {
            $attempts = ++$hash['attempts'];
            $values = [
                'attempts' => $attempts,
                'email' => $email
            ];
            $sql_attempts = "UPDATE users SET attempts = :attempts WHERE email = :email";
            $query = $this->pdo->prepare($sql_attempts);
            $query->execute($values);
            $result['password'] = 'Неверный пароль';
        }
        return $result;
    }

    /**
     * Метод получает данные пользователя по email
     */
    public function getParameters($email) {
        $values = ['email' => $email];
        $sql = "SELECT name, surname, avatar, about FROM users WHERE email = :email";
        $query = $this->pdo->prepare($sql);
        $query->execute($values);
        $params = $query->fetch(PDO::FETCH_ASSOC);
        return $params;
    }


    /**
     * Метод обновляет параметры пользователя
     */
    public function update($email, $new_parameters) {

        $params = $this->getParameters($email);

        $result = [];
        if( $params['name'] == $new_parameters['name'] && $params['surname'] == $new_parameters['surname']
            && $params['about'] == $new_parameters['about'] && empty($_FILES['avatar']['name']) ) {
            $result['changes'] = 'Нет изменений';
        } else {

            $types = ['image/jpeg'];
            if ($_FILES['avatar']['type'] != '' && !in_array($_FILES['avatar']['type'], $types)) {
                $result['avatar'] = 'Загрузите изображение в формате .jpg';
                return $result;
            };

            if (!$new_parameters['password']) {
                $result['password'] = 'Введите пароль';
                return $result;
            };

            $values = ['email' => $email];
            $sql = "SELECT hashpwd FROM users WHERE email = :email";
            $query = $this->pdo->prepare($sql);
            $query->execute($values);
            $hash = $query->fetch(PDO::FETCH_ASSOC);
            if (!password_verify($new_parameters['password'], $hash['hashpwd'])) {
                $result['password'] = 'Неверный пароль';
                return $result;
            }

            if ($_FILES['avatar']['size'] != 0) {
                //Сохранение изображения
                $path = 'storage/' . md5(time()) . '.jpg';
                move_uploaded_file($_FILES['avatar']['tmp_name'], '../' . $path);

                //Ресайз изображения
                $filename = '../' . $path;
                list($width, $height) = getimagesize($filename);


                if ($width > $height) {
                    $new_width = 200;
                    $new_height = $new_width * ($height/$width);
                } else {
                    $new_height = 200;
                    $new_width = $new_height * ($width/$height);
                }

                $thumb = imagecreatetruecolor($new_width, $new_height);

                $source = imagecreatefromjpeg($filename);
                imagecopyresized($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                unlink('../' . $path);
                imagejpeg($thumb, '../' . $path, 100);
            } else {
                $path = $params['avatar'];
            }

            $values = [
                'name' => htmlspecialchars($new_parameters['name']),
                'surname' => htmlspecialchars($new_parameters['surname']),
                'about' => htmlspecialchars($new_parameters['about']),
                'avatar' => $path,
                'email' => $email
            ];
            $sql = 'UPDATE `users` SET `name` = :name, `surname` = :surname, `about` = :about, avatar = :avatar WHERE `users`.`email` = :email;';
            $query = $this->pdo->prepare($sql);
            $query->execute($values);
            $result['success'] = 'Изменения сохранены';
        }
        return $result;
    }

    /**
     * Метод получает данные всех пользователей
     */
    public function getUsersParams() {
        $sql = 'SELECT email, name, surname, avatar, about FROM users';
        $query = $this->pdo->query($sql);
        $result = $query->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
}
