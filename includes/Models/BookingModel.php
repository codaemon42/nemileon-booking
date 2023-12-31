<?php

namespace ONSBKS_Slots\Includes\Models;

use ONSBKS_Slots\Includes\Status\BookingStatus;
use ONSBKS_Slots\RestApi\Exceptions\InvalidBookingStatusException;

class BookingModel
{
    // changing the properties needs attention to the BookingsEntity
    private int $id;
    private string $user_id;
    private string $finger_print;
    private string $name; // productName
    private string $booking_date;
    private string $seats;
    private int $product_id;
    private string $headers;
    private string $top_header;
    private int $total_price;
    private string $status; // pending-payment | active | completed | cancelled
    private Slot $template;
    private bool $expired;
    private ?string $expiresIn;

    private array $data = [];

	/**
	 * @throws InvalidBookingStatusException
	 */
	public function __construct( $data = null )
    {
        $this->setData([
            'id' => 0,
            'user_id' => '',
            'finger_print' => '',
            'name' => '',
            'booking_date' => '',
            'seats' => '',
            'product_id' => 0,
            'headers' => '',
            'top_header' => '',
            'total_price' => 0,
            'status' => BookingStatus::PENDING_PAYMENT,
            'template' => new Slot(),
            'expired' => false,
            'expires_in' => null
        ]);

        if($data instanceof self){
            $this->setId( $data->getId() );
            $this->setUserId( $data->getUserId() );
            $this->setFingerPrint( $data->getFingerPrint() );
            $this->setName( $data->getName() );
            $this->setBookingDate( $data->getBookingDate() );
            $this->setSeats( $data->getSeats() );
            $this->setProductId( $data->getProductId() );
            $this->setHeaders( $data->getHeaders() );
            $this->setTopHeader( $data->getTopHeader() );
            $this->setTotalPrice( $data->getTotalPrice() );
            $this->setStatus( $data->getStatus() );
            $this->setExpired( $data->isExpired() );
            $this->setExpiresIn( $data->getExpiresIn() );
            $this->setTemplate( $data->getData()['template'] );
        }
        else{
            if($data == null) {
                $data = $this->data;
            }
            $this->setId( $data['id'] );
            $this->setUserId( $data['user_id'] );
            $this->setFingerPrint( $data['finger_print'] );
            $this->setName( $data['name'] );
            $this->setBookingDate( $data['booking_date'] );
            $this->setSeats( $data['seats'] );
            $this->setProductId( $data['product_id'] );
            $this->setHeaders( $data['headers'] );
            $this->setTopHeader( $data['top_header'] );
            $this->setTotalPrice( $data['total_price'] );
            $this->setStatus( $data['status'] );
            $this->setExpired( $data['expired'] );
            $this->setExpiresIn( $data['expires_in'] );
            $this->setTemplate( $data['template'] );
        }
    }


    /**
     * List of BookingModel Object
     * @param array $initialValue
     * @return Slot[]
     * @throws InvalidBookingStatusException
     */
    public static function List(array $initialValue = []): array
    {
        $arr = [new self()];
        array_shift($arr);
        if(count($initialValue)){
            foreach ($initialValue as $iv){
                $arr[] = new self($iv);
            }
        }
        return $arr;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->data['id'] = $id;
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->user_id;
    }

    /**
     * @param string $user_id
     */
    public function setUserId(string $user_id): void
    {
        $this->data['user_id'] = $user_id;
        $this->user_id = $user_id;
    }

    /**
     * @return string
     */
    public function getFingerPrint(): string
    {
        return $this->finger_print;
    }

    /**
     * @param string $finger_print
     */
    public function setFingerPrint(string $finger_print): void
    {
        $this->data['finger_print'] = $finger_print;
        $this->finger_print = $finger_print;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->data['name'] = $name;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getBookingDate(): string
    {
        return $this->booking_date;
    }

    /**
     * @param string $booking_date
     */
    public function setBookingDate(string $booking_date): void
    {
        $this->data['booking_date'] = $booking_date;
        $this->booking_date = $booking_date;
    }

    /**
     * @return string
     */
    public function getSeats(): string
    {
        return $this->seats;
    }

    /**
     * @param string $seats
     */
    public function setSeats(string $seats): void
    {
        $this->data['seats'] = $seats;
        $this->seats = $seats;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->product_id;
    }

    /**
     * @param int $product_id
     */
    public function setProductId(int $product_id): void
    {
        $this->data['product_id'] = $product_id;
        $this->product_id = $product_id;
    }

    /**
     * @return string
     */
    public function getHeaders(): string
    {
        return $this->headers;
    }

    /**
     * @param string $headers
     */
    public function setHeaders(string $headers): void
    {
        $this->data['headers'] = $headers;
        $this->headers = $headers;
    }

    /**
     * @return string
     */
    public function getTopHeader(): string
    {
        return $this->top_header;
    }

    /**
     * @param string $top_header
     */
    public function setTopHeader(string $top_header): void
    {
        $this->data['top_header'] = $top_header;
        $this->top_header = $top_header;
    }

    /**
     * @return int
     */
    public function getTotalPrice(): int
    {
        return $this->total_price;
    }

    /**
     * @param int $total_price
     */
    public function setTotalPrice(int $total_price): void
    {
        $this->data['total_price'] = $total_price;
        $this->total_price = $total_price;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @throws InvalidBookingStatusException
     */
    public function setStatus(string $status): void
    {
        $this->data['status'] = BookingStatus::parse($status);
        $this->status = BookingStatus::parse($status);
    }


    /**
     * @return Slot
     */
    public function getTemplate(): Slot
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     */
    public function setTemplate($template): void
    {
        $this->data['template'] = $template instanceof Slot ? $template->getData() : $template;
        $this->template = new Slot($template);
    }

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expired;
    }

    /**
     * @param bool $expired
     */
    public function setExpired(bool $expired): void
    {
        $this->data['expired'] = $expired;
        $this->expired = $expired;
    }

    /**
     * @return string|null
     */
    public function getExpiresIn(): ?string
    {
        return $this->expiresIn;
    }

    /**
     * @param string|null $expiresIn
     */
    public function setExpiresIn(?string $expiresIn): void
    {
        $this->data['expires_in'] = $expiresIn;
        $this->expiresIn = $expiresIn;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }


}
