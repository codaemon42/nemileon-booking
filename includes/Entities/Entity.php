<?php

namespace ONSBKS_Slots\Includes\Entities;

class Entity
{
    public $_wpdb;
    public string $table_name = "";

    public function __construct($table_name)
    {
        global $wpdb;
        $this->_wpdb = $wpdb;
        $this->table_name = $this->_wpdb->prefix . $table_name;

    }


    /**
     * @return \QM_DB|\wpdb
     */
    public function getWpdb(): \wpdb
    {
        return $this->_wpdb;
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->table_name;
    }


    public function findAll($per_page = 100, $paged = 1): array
    {
        // Calculate the offset for pagination
        $offset = ($paged - 1) * $per_page;

        // Query to retrieve the entries with pagination
        $query = "SELECT * FROM $this->table_name ORDER BY id DESC LIMIT $per_page OFFSET $offset;";
        $results = $this->_wpdb->get_results($query, ARRAY_A);

        return $results;
    }


    public function findById(string $id)
    {
        // Prepare the query to retrieve the entry by ID
        $query = $this->_wpdb->prepare("SELECT * FROM $this->table_name WHERE id = %d", $id);

        // Retrieve the entry from the table
        return $this->_wpdb->get_row($query, ARRAY_A);
    }


    public function create(array $data): int
    {
        // Insert data into the table
        $this->_wpdb->insert($this->table_name, $data);

        // Return the ID of the inserted row (optional - useful if you need the ID later)
        return $this->_wpdb->insert_id;
    }


    public function update($id, $data)
    {
        // Prepare the WHERE clause to identify the row to update
        $where = array('id' => $id);

        // Update the data in the table
        $this->_wpdb->update($this->table_name, $data, $where);

        // Return the number of rows affected (optional - useful for checking if the update was successful)
        return $this->_wpdb->rows_affected;
    }


    public function delete($id): int
    {
        // Prepare the WHERE clause to identify the row to update
        $where = array('id' => $id);

        // Update the data in the table
        $this->_wpdb->delete($this->table_name, $where);

        // Return the number of rows affected (optional - useful for checking if the update was successful)
        return $this->_wpdb->rows_affected;
    }
}