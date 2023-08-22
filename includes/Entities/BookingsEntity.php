<?php
namespace ONSBKS_Slots\Includes\Entities;


use ONSBKS_Slots\Includes\Models\BookingModel;

class BookingsEntity extends Entity
{

    public function __construct($db_init = true)
    {
        parent::__construct("nemileon_bookings");

        if($db_init){
            $this->db_init();
        }
        $tableName = $this->getTableName();
    }


    private function db_init(): void
    {
        // changing the sql needs attention to the BookingModel
        $sql = "CREATE TABLE IF NOT EXISTS $this->table_name (
            id INT NOT NULL AUTO_INCREMENT,
            user_id VARCHAR(255),
            name VARCHAR(255),
            booking_date DATE,
            seats INT,
            product_id VARCHAR(100),
            headers LONGTEXT,
            top_header VARCHAR(255),
            status VARCHAR(255),
            template LONGTEXT,
            PRIMARY KEY (id)
        );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta( $sql );
    }

    /**
     * @param string $id
     * @return BookingModel|null
     */
    public function findById(string $id): ?BookingModel
    {
        $bookingData = parent::findById($id);
        if(!$bookingData) return null;
        return new BookingModel($bookingData);
    }


    public function createBooking(BookingModel $bookingModel): int
    {
        $data = $bookingModel->getData();
        return parent::create((object)$data);
    }


}