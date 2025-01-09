<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Seassion;
use Framework\Validation;
use Framework\Session;

class UserController
{
    protected $db;
    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }
    /**
     * Show the Login page
     * @return void
     */
    public function login()
    {
        loadView('users/login');
    }
    /**
     * Show the register page
     * @return void
     */
    public function create()
    {
        loadView('users/create');
    }

    /**
     * Store user in database
     * @return void
     */
    public function store()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $city = $_POST['city'];
        $password = $_POST['password'];
        $passwordConformation = $_POST['password_confirmation'];
        $state = $_POST['state'];

        $errors = [];

        // Validation
        if (!Validation::email($email)) {
            $errors['email'] = 'Please enter a valid email address';
        }
        if (!Validation::string($name, 2, 50)) {
            $errors['name'] = 'Name must be between 2 and 50 characters';
        }
        if (!Validation::string($password, 6, 50)) {
            $errors['password'] = 'Password must be at least 6 characters';
        }
        if (!Validation::match($password, $passwordConformation)) {
            $errors['password_confirmation'] = 'Passwords do not match';
        }

        // If there are validation errors, reload the form with errors and old input
        if (!empty($errors)) {
            loadView('users/create', [
                'errors' => $errors,
                'user' => [
                    'name' => $name,
                    'email' => $email,
                    'city' => $city,
                    'state' => $state,
                ]
            ]);
            exit;
        }

        // Check if email already exists
        $params = ['email' => $email];
        $user = $this->db->query('SELECT * FROM users WHERE email = :email', $params)->fetch();

        if ($user) {
            $errors['email'] = 'This email already exists';
            loadView('users/create', [
                'errors' => $errors,
                'user' => [
                    'name' => $name,
                    'email' => $email,
                    'city' => $city,
                    'state' => $state,
                ]
            ]);
            exit;
        }

        // create user account 
        $params = [
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];
        $this->db->query('INSERT INTO users (name,email,city,state,password)values(:name,:email,:city,:state,:password)', $params);

        $userId = $this->db->conn->lastInsertId();
        Seassion::set($user, [
            'id' => $userId,
            'email' => $email,
            'name' => $name,
            'city' => $city,
            'state' => $state
        ]);
        inspectAndDie(Seassion::get($user));
        redirect('/');
    }
}
