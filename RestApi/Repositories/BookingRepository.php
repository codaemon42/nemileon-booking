<?php

namespace ONSBKS_Slots\RestApi\Repositories;

use ONSBKS_Slots\Includes\Entities\BookingsEntity;
use ONSBKS_Slots\Includes\Models\BookingModel;
use ONSBKS_Slots\RestApi\Exceptions\BookingCreateException;
use PHPUnit\Exception;

class BookingRepository extends BookingsEntity
{

    public function __construct()
    {
        parent::__construct(false);
    }

    /**
     * @Override findById
     * @param string $id
     * @return BookingModel|null
     */
    public function findById(string $id): ?BookingModel
    {
        $bookingData = parent::findById($id);
        if(!$bookingData) return null;

        $bookingData['template'] = unserialize($bookingData['template']);
        return new BookingModel($bookingData);
    }

    /**
     * @Override createBooking
     * @param BookingModel $bookingModel
     * @return int
     * @throws BookingCreateException
     */
    public function createBooking(BookingModel $bookingModel): int
    {
        try {
            $data = $bookingModel->getData();
            $data['template'] = serialize($data['template']);
            return parent::create($data);
        } catch (Exception $e){
            throw new BookingCreateException($e->getMessage());
        }
    }

    /**
     * @param string $userId
     * @return array
     */
    public function findAllByUserId(string $userId): array
    {
        // Prepare the query to retrieve the entry by ID
        $query = $this->_wpdb->prepare("SELECT * FROM $this->table_name WHERE user_id = %d", $userId);

        // Retrieve the entry from the table
        return $this->_wpdb->get_results($query, ARRAY_A);
    }

    /**
     * @param string $userId
     * @param string $fingerPrint
     * @param int $per_page
     * @param int $paged
     * @return array
     */
    public function findAllByUserIdOrFingerPrint(int $userId, string $fingerPrint, int $per_page = 10, int $paged = 1): array
    {
        // Calculate the offset for pagination
        $offset = ($paged - 1) * $per_page;

        // Prepare the query to retrieve the entry by ID
        $query =
            $this->_wpdb->prepare(
                "SELECT * FROM $this->table_name WHERE user_id = %d OR finger_print = %s  ORDER BY id DESC LIMIT %d OFFSET %d;",
                $userId,
                $fingerPrint,
                $per_page,
                $offset
            );

        // Retrieve the entry from the table
        return $this->_wpdb->get_results($query, ARRAY_A);
    }

}