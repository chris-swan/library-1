<?php
    class Checkout
    {
        private $due_date;
        private $copy_id;
        private $patron_id;
        private $id;

        function __construct($due_date, $copy_id, $patron_id, $id = NULL)
        {
            $this->due_date = $due_date;
            $this->copy_id = $copy_id;
            $this->patron_id = $patron_id;
            $this->id = $id;
        }

        function setDueDate($new_due_date)
        {
            $this->due_date = $new_due_date;
        }

        function setCopyId($new_copy_id)
        {
            $this->copy_id = $new_copy_id;
        }

        function setPatronId($new_patron_id)
        {
            $this->patron_id = $new_patron_id;
        }


        function getDueDate()
        {
            return $this->due_date;
        }

        function getCopyId()
        {
            return $this->copy_id;
        }

        function getPatronId()
        {
            return $this->patron_id;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO checkouts(due_date, copy_id, patron_id)
            Values('{$this->getDueDate()}', {$this->getCopyId()}, {$this->getPatronId()});");
            $this->id = $GLOBALS['DB']->lastInsertID();
        }

        static function getAll()
        {
            $returned_checkouts = $GLOBALS['DB']->query("SELECT * FROM checkouts;");
            $checkouts = array();
            foreach($returned_checkouts as $checkout) {
                $due_date = $checkout['due_date'];
                $copy_id = $checkout['copy_id'];
                $patron_id = $checkout['patron_id'];
                $id = $checkout['id'];
                $new_checkout = new Checkout($due_date, $copy_id, $patron_id, $id);
                array_push($checkouts, $new_checkout);
            }
            return $checkouts;
        }

        static function deleteAll()
        {
            $GLOBALS ['DB']->exec("DELETE FROM checkouts;");
        }

        static function findDueDate($search_id)
        {
            $found_checkout = null;
            $checkouts = Checkout::getAll();
            foreach($checkouts as $checkout) {
                $checkout_id = $checkout->getId();
                if ($checkout_id == $search_id) {
                    $found_checkout = $checkout;
                }
            }
            return $found_checkout;
        }

        function updateDueDate($new_date)
        {
            $GLOBALS['DB']->exec("UPDATE checkouts SET due_date = '{$new_date}' WHERE id = {$this->getID()};");
        }

    }


 ?>
