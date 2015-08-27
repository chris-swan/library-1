<?php
    class Patron
    {
        private $patron_name;
        private $id;

        function __construct($patron_name, $id = null)
        {
            $this->patron_name = $patron_name;
            $this->id = $id;
        }
        function setpatron($new_patron)
        {
            $this->patron_name = $new_patron;
        }
        function getpatron()
        {
            return $this->patron_name;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO patrons (patron_name)
            VALUES ('{$this->getpatron()}');");
            $this->id = $GLOBALS['DB']->lastInsertID();
        }

        static function getAll()
        {
            $returned_patrons = $GLOBALS['DB']->query("SELECT * FROM patrons;");
            var_dump($returned_patrons);
            $patrons = array();
            foreach($returned_patrons as $patron_name) {
                $patron_name = $patron_name['patron_name'];
                $id = $patron_name['id'];
                $new_patron = new Patron($patron_name, $id);
                array_push($patrons, $new_patron);
            }
            return $patrons;
        }

        static function deleteAll()
        {
            $GLOBALS ['DB']->exec("DELETE FROM patrons;");
        }

        static function find($search_id)
        {
            $found_patron = null;
            $patrons = Patron::getAll();
            foreach($patrons as $patron_name) {
                $patron_id = $patron_name->getId();
                if ($patron_id == $search_id) {
                    $found_patron = $patron_name;
                }
            }
            return $found_patron;
        }

        function addCheckout($copy_id)
        {
            $GLOBALS['DB']->exec("INSERT INTO checkouts (copy_id, patron_id, due_date)
                VALUES ({$copy_id}, {$this->id}, '{CURDATE(), interval 1 month}')");
        }

        function getCheckouts()
        {
            $returned_checkouts = $GLOBALS['DB']->query("SELECT books.* FROM
                books JOIN copies ON (books.id = copies.book_id)
                JOIN checkouts ON (checkouts.copy_id = copies.id)
                JOIN patrons ON (patrons.id = checkouts.patron_id)
                WHERE patrons.id = {$this->getId()}");
                var_dump($returned_checkouts);
            $checkouts = array();
            foreach($returned_checkouts as $checkout)
            {
                $title = $checkout['title'];
                $id = $checkout['id'];
                $new_checkout = new Book($title, $id);
                array_push($chekcouts, $new_checkout);
            }
            return $checkouts;
        }

        // function deleteBook($book_id)
        // {
        //     $GLOBALS['DB']->exec("DELETE FROM authors_books WHERE
        //         book_id = {$book_id} AND author_id = {$this->id}");
        // }

        function deleteAllBooks()
        {
            $GLOBALS['DB']->exec("DELETE FROM authors_books WHERE author_id = {$this->id}");
        }

        //Update patron_name
        function updatepatron($new_patron)
        {
            $GLOBALS['DB']->exec("UPDATE authors SET patron_name = '{$new_patron}'
                WHERE id = {$this->getId()}");
            $this->setpatron($new_patron);
        }

    }
 ?>
