<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Seassion;
use Framework\Authorization;

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
        $listings = $this->db->query('SELECT * FROM listings ORDER BY created_at DESC')->fetchAll();
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
        $newListingData['user_id'] = Seassion::get('user')['id'];
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
            Seassion::setFlashMessage('success_message', 'Listing Created successfully');

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
        // Check if listing exists
        if (!$listing) {
            ErrorController::notFound();
            return;
        }
        // inspectAndDie($listing->user_id);

        // Autherization
        if (!Authorization::isOwner($listing->user_id)) {

            // inspectAndDie('you are not authorized');
            Seassion::setFlashMessage('error_message', 'You ar enot authorized to delete this listing');
            return redirect('/listings/' . $listing->id);
        }

        $this->db->query('DELETE from listings where id =:id', $params);


        // Set flash Message
        Seassion::setFlashMessage('success_message', 'Listing Delete successfully');

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

        if (!Authorization::isOwner($listing->user_id)) {

            // inspectAndDie('you are not authorized');
            Seassion::setFlashMessage('error_message', 'You ar enot authorized to delete this listing');
            return redirect('/listings/' . $listing->id);
        }


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

        if (!Authorization::isOwner($listing->user_id)) {

            // inspectAndDie('you are not authorized');
            Seassion::setFlashMessage('error_message', 'You ar enot authorized to delete this listing');
            return redirect('/listings/' . $listing->id);
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
            Seassion::setFlashMessage('success_message', 'Listing updated succuess');

            redirect('/listings/' . $id);
        }
    }


    /**
     * Search Listings by keywords/location
     * @return void
     */
    public function search()
    {
        // inspectAndDie($_GET);
        $keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : '';
        $location = isset($_GET['location']) ? trim($_GET['location']) : '';
        // inspectAndDie($location);
        $query = "SELECT * FROM listings where 
        (title LIKE :keywords  OR description like :keywords OR tags LIKE :keywords 
            OR company LIKE :keywords ) AND (city LIKE :location OR state LIKE :location)";
        $params = [
            'keywords' => "%{$keywords}%",
            'location' => "%{$location}%"
        ];
        $listings = $this->db->query($query, $params)->fetchAll();
        // inspectAndDie($listing);
        loadView('/listings/index', [
            'listings' => $listings,
            'keywords' => $keywords,
            'location' => $location
        ]);
    }
}
