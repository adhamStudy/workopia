<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

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
        //Validation
        if (!Validation::email($email)) {
            $errors['email'] = 'Please enter a valid email address';
        }
        if (!Validation::string($name, 2, 50)) {
            $errors['name'] = 'name must be between 2 and 50 characters';
        }
        if (!Validation::string($password, 6, 50)) {
            $errors['password'] = 'password must be at least 6 characters';
        }
        if (!Validation::match($password, $passwordConformation)) {
            $errors['password_confirmation'] = 'must enter Identical passwords';
        }
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
        }
    }
}
