<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

class ListingController
{
    protected $db;
    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    public function index()
    {
        $listings = $this->db->query('SELECT * FROM listings')->fetchAll();
        // inspectAndDie(Validation::match('123', '123'));

        loadView('/listings/index', [
            'listings' => $listings
        ]);
    }
    public function create()
    {
        loadView('listings/create');
    }
    public function show($params)
    {

        $id = $params['id'] ?? '';

        // inspect($id);

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('select * from listings where id =:id', $params)->fetch();

        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }
        // inspectAndDie($listing);

        loadView('listings/show', [
            'listing' => $listing
        ]);
    }
    /**
     * Store data in database
     * @return void
     * 
     */
    public function store()
    {
        $allowedFields = [
            'title',
            'description',
            'salary',
            'tags',
            'company',
            'address',
            'city',
            'state',
            'phone',
            'email',
            'requirements',
            'benefits'
        ];
        $newListingData = array_intersect_key($_POST, array_flip($allowedFields));
        $newListingData['user_id'] = 1;
        $newListingData = array_map('sanitize', $newListingData);
        $requiredFields = ['title', 'description', 'email', 'phone', 'city', 'state', 'salary'];
        $errors = [];
        foreach ($requiredFields as $field) {
            if (empty($newListingData[$field]) || !Validation::string($newListingData[$field])) {
                $errors[$field] = ucfirst($field) . ' is Required';
            }
        }
        if (!empty($errors)) {
            loadView('listings/create', [
                'errors' => $errors,
                'listing' => $newListingData
            ]);
        } else {
            // Submit data
            //   $this->db->query('INSERT INTO listings (title,description,salary,
            //   tags,company,address,
            //   city,state,phone,email,
            //   requirements,user_id)VALUES (:title,:description,:salary,
            //   :tags,:company,:address,
            //   :city,:state,:phone,:email,
            //   :requirements,:user_id)');
            $fields = [];
            foreach ($newListingData as $field => $value) {
                $fields[] = $field;
            }
            $fields = implode(', ', $fields);
            $values = [];
            foreach ($newListingData as $field => $value) {
                if ($value === null) {
                    $newListingData[$value] = null;
                }
                $values[] = ':' . $field;
            }
            $values = implode(', ', $values);
            // inspectAndDie($values);
            $query = "insert into listings ({$fields})values({$values})";
            $this->db->query($query, $newListingData);
            redirect('/listings');
        }
    }
    /**
     * Delete a listing
     * @param array $params
     * @return void
     */
    public function destroy($params)
    {

        $id = $params['id'];
        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('SELECT * FROM listings where id = :id', $params)->fetch();
        if (!$listing) {
            ErrorController::notFound();
        }

        $this->db->query('DELETE from listings where id =:id', $params);


        // Set flash Message
        $_SESSION['success_message'] = 'Listing Delete successfully';

        redirect('/listings');
    }


    public function edit($params)
    {
        $id = $params['id'] ?? '';

        // inspect($id);

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('select * from listings where id =:id', $params)->fetch();

        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }
        // inspectAndDie($listing);

        loadView('listings/edit', [
            'listing' => $listing
        ]);
    }

    /**
     * update Listing
     * @param array $params
     * @return void
     */
    public function update($params)
    {
        $id = $params['id'] ?? '';

        // inspect($id);

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('select * from listings where id =:id', $params)->fetch();

        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }
        $allowedFields = [
            'title',
            'description',
            'salary',
            'tags',
            'company',
            'address',
            'city',
            'state',
            'phone',
            'email',
            'requirements',
            'benefits'
        ];
        $updateValues = [];
        $updateValues = array_intersect_key($_POST, array_flip($allowedFields));
        $updateValues = array_map('sanitize', $updateValues);
        $requiredFields = ['title', 'description', 'salary', 'email', 'city', 'state'];
        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($updateValues[$field]) || !Validation::string($updateValues[$field])) {
                $errors[$field] = ucfirst($field) . ' is required ';
            }
        }

        if (!empty($errors)) {
            loadView('listings/edit', [
                'listing' => $listing,
                'errors' => $errors
            ]);
            exit;
        } else {
            // submit to database
            $updateFields = [];
            foreach (array_keys($updateValues) as $field) {
                $updateFields[] = "{$field} = :{$field}";
            }
            $updateValues['id'] = $id;
            $updateFields = implode(', ', $updateFields);
            $updateQuery = "UPDATE listings SET $updateFields where id = :id";
            $this->db->query($updateQuery, $updateValues);
            $_SESSION['success_message'] = 'Listing Delete successfully';
            redirect('/listings/' . $id);
        }
    }
}
