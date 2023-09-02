<?php

namespace ONSBKS_Slots\RestApi\Repositories;

use Cassandra\Date;
use ONSBKS_Slots\Includes\Entities\BookingsEntity;
use ONSBKS_Slots\Includes\Models\BookingModel;
use ONSBKS_Slots\RestApi\Exceptions\BookingCreateException;
use ONSBKS_Slots\RestApi\Exceptions\BookingNotFound;
use ONSBKS_Slots\RestApi\Exceptions\BookingProcessException;
use PHPUnit\Exception;

class BookingRepository extends BookingsEntity
{

    public function __construct()
    {
        parent::__construct(false);
    }

    public function findAll($per_page = 100, $paged = 1): array
    {
        $bookings = parent::findAll($per_page, $paged);
        foreach ($bookings as $bookingKey => $booking)
        {
            $bookings[$bookingKey]['template'] = maybe_unserialize($booking['template']);
        }
        return $bookings;
    }


    /**
     * @Override findById
     * @param string $id
     * @return BookingModel|null
     * @throws \ONSBKS_Slots\RestApi\Exceptions\InvalidBookingStatusException
     */
    public function findById(string $id): ?BookingModel
    {
        $bookingData = parent::findById($id);
        if(!$bookingData) return null;

        $bookingData['template'] = unserialize($bookingData['template']);
        return new BookingModel($bookingData);
    }


    /**
     * @throws BookingNotFound
     * @throws \ONSBKS_Slots\RestApi\Exceptions\InvalidBookingStatusException
     */
    public function findBookingByBookingIdAndUserIdOrFingerPrint(string $bookingId, int $userId, string $fingerPrint, $throwable = false): ?BookingModel
    {
        // Prepare the query to retrieve the entry by ID
        $query = $this->_wpdb->prepare(
            "SELECT * FROM $this->table_name WHERE id = %d AND (user_id = %d OR finger_print = %s)",
            $bookingId,
            $userId,
            $fingerPrint
        );

        // Retrieve the entry from the table
        $booking = $this->_wpdb->get_row($query, ARRAY_A);
        $booking['template'] = unserialize($booking['template']);
        if(!$booking && $throwable) throw new BookingNotFound();
        if(!$booking) return null;
        return new BookingModel($booking);
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
        $query = "";
        if(!$userId) {
            $query =
                $this->_wpdb->prepare(
                    "SELECT * FROM $this->table_name WHERE finger_print = %s  ORDER BY id DESC LIMIT %d OFFSET %d;",
                    $fingerPrint,
                    $per_page,
                    $offset
                );
        } else {
            $query =
                $this->_wpdb->prepare(
                    "SELECT * FROM $this->table_name WHERE user_id = %d  OR finger_print = %s  ORDER BY id DESC LIMIT %d OFFSET %d;",
                    $userId,
                    $fingerPrint,
                    $per_page,
                    $offset
                );
        }

        // Retrieve the entry from the table
        $bookings = $this->_wpdb->get_results($query, ARRAY_A);
        foreach ($bookings as $bookingKey => $booking)
        {
            $bookings[$bookingKey]['template'] = unserialize($booking['template']);
        }
        return $bookings;
    }


    /**
     * Count Number of bookings by UserId and fingerprint
     * @param int $userId
     * @param string $fingerPrint
     * @return int
     */
    public function countAllByUserIdOrFingerPrint(int $userId, string $fingerPrint): int
    {

        // Prepare the query to retrieve the entry by ID
        $query =
            $this->_wpdb->prepare(
                "SELECT COUNT(*) FROM $this->table_name WHERE user_id = %d OR finger_print = %s;",
                $userId,
                $fingerPrint
            );

        // Get the count of matching rows
        $count = $this->_wpdb->get_var($query);

        return intval($count);
    }

    /**
     * @throws BookingProcessException
     */
    public function update($id, $data, $throwable = false)
    {
        if($data['id']) unset($data['id']);
        $data['template'] = serialize($data['template']);
        $updated = parent::update($id, $data);
        if(!$updated && $throwable) throw new BookingProcessException();
        return $updated; // TODO: Change the autogenerated stub
    }


}