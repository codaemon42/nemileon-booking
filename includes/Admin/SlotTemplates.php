<?php

namespace ONSBKS_Slots\Includes\Admin;

class SlotTemplates
{
    private $_wpdb;
    private $table_name = "";

    public function __construct($db_init = true)
    {
        global $wpdb;
        $this->_wpdb = $wpdb;
        $this->table_name = $this->_wpdb->prefix . "nemileon_slot_templates";

        if($db_init){
            $this->db_init();
        }

    }

    public function db_init()
    {
        $sql = "CREATE TABLE IF NOT EXISTS $this->table_name (
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(255),
            template LONGTEXT,
            PRIMARY KEY (id)
        );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    public function find_all($per_page = 100, $paged = 1) {
        // Calculate the offset for pagination
        $offset = ($paged - 1) * $per_page;

        // Query to retrieve the entries with pagination
        $query = "SELECT * FROM $this->table_name ORDER BY id DESC LIMIT $per_page OFFSET $offset;";
        $results = $this->_wpdb->get_results($query, ARRAY_A);

        foreach ($results as $result){
            $result['template'] = json_decode($result['template'], true);
        }

        return $results;
    }

    public function find_one($id) {
        // Prepare the query to retrieve the entry by ID
        $query = $this->_wpdb->prepare("SELECT * FROM $this->table_name WHERE id = %d", $id);

        // Retrieve the entry from the table
        $entry = $this->_wpdb->get_row($query, ARRAY_A);

        if ($entry) {
            // Decode the JSON data to PHP data
            $entry['template'] = json_decode($entry['template'], true);
        }

        return $entry;
    }


    public function create($template, $name) {
        // Prepare data for insertion
        $data = array(
            'name' => $name,
            'template' => wp_json_encode($template), // Encode the data as JSON before inserting
        );

        // Format data types for safe insertion
//        $data_formats = array('%s');

        // Insert data into the table
        $this->_wpdb->insert($this->table_name, $data);

        // Return the ID of the inserted row (optional - useful if you need the ID later)
        return $this->_wpdb->insert_id;
    }

    public function update($id, $template) {

        // Prepare data for update
        $data = array(
            'template' => wp_json_encode($template),
        );

        // Prepare the WHERE clause to identify the row to update
        $where = array('id' => $id);

        // Update the data in the table
        $this->_wpdb->update($this->table_name, $data, $where);

        // Return the number of rows affected (optional - useful for checking if the update was successful)
        return $this->_wpdb->rows_affected;
    }


    public function delete($id) {
        // Prepare the WHERE clause to identify the row to update
        $where = array('id' => $id);

        // Update the data in the table
        $this->_wpdb->delete($this->table_name, $where);

        // Return the number of rows affected (optional - useful for checking if the update was successful)
        return $this->_wpdb->rows_affected;
    }

}