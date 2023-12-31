<?php
namespace ONSBKS_Slots\Includes\Entities;


class BookingsEntity extends Entity
{

    public function __construct($db_init = true)
    {
        parent::__construct("nemileon_bookings");

        if($db_init){
            $this->db_init();
        }
    }


    private function db_init(): void
    {
        // changing the sql needs attention to the BookingModel
        $sql = "CREATE TABLE IF NOT EXISTS $this->table_name (
            id INT NOT NULL AUTO_INCREMENT,
            user_id INT,
            finger_print VARCHAR(255),
            name VARCHAR(255),
            booking_date DATE,
            seats INT,
            product_id VARCHAR(100),
            headers LONGTEXT,
            top_header VARCHAR(255),
            total_price INT,
            status VARCHAR(255),
            template LONGTEXT,
            expired BOOLEAN NOT NULL DEFAULT FALSE,
            expires_in TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta( $sql );

        // $this->alterTables();
    }

    private function alterTables(): void
    {
        $colExpired = "ALTER TABLE $this->table_name ADD `expired` BOOLEAN NOT NULL DEFAULT FALSE AFTER `template`; ";
        $this->getWpdb()->query($colExpired);

        $colExpiresIn = "ALTER TABLE $this->table_name ADD `expires_in` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `expired`; ";
        $this->getWpdb()->query($colExpiresIn);
    }
    
}
