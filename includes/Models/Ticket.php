<?php

namespace ONSBKS_Slots\Includes\Models;

class Ticket
{
    private int $id;
    private string $name; // issuer name
    private string $email;
    private string $phone;
    private string $booking_date;
    private string $seats;
    private int $total_price;
    private string $product_name;
    private Slot $template;

    private array $data = [];

    public function __construct( $data = null )
    {
        $this->setData([
            'id' => 0,
            'name' => '',
            'email' => '',
            'phone' => '',
            'booking_date' => '',
            'seats' => '',
            'total_price' => 0,
            'product_name' => 0,
            'template' => new Slot(),
        ]);

        if($data instanceof self){
            $this->setId( $data->getId() );
            $this->setName( $data->getName() );
            $this->setPhone( $data->getPhone() );
            $this->setEmail( $data->getEmail() );
            $this->setBookingDate( $data->getBookingDate() );
            $this->setSeats( $data->getSeats() );
            $this->setTotalPrice( $data->getTotalPrice() );
            $this->setProductName( $data->getProductName() );
            $this->setTemplate( $data->getData()['template'] );

        }
        else{
            if($data == null) {
                $data = $this->data;
            }
            $this->setId( $data['id'] );
            $this->setName( $data['name'] );
            $this->setEmail( $data['email'] );
            $this->setPhone( $data['phone'] );
            $this->setBookingDate( $data['booking_date'] );
            $this->setSeats( $data['seats'] );
            $this->setTotalPrice( $data['total_price'] );
            $this->setProductName( $data['product_name'] );
            $this->setTemplate( $data['template'] );
        }
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->data['name'] = $name ?: '';
        $this->name = $name ?: '';
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->data['email'] = $email;
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     */
    public function setPhone(?string $phone): void
    {
        $this->data['phone'] = $phone ?: '';
        $this->phone = $phone ?: '';
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
    public function getProductName(): string
    {
        return $this->product_name;
    }

    /**
     * @param string $product_name
     */
    public function setProductName(string $product_name): void
    {
        $this->data['product_name'] = $product_name;
        $this->product_name = $product_name;
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

    public function __toString(): string
    {
        return sprintf(
            "Ticket Information:\n" .
            "ID: %d\n" .
            "Name: %s\n" .
            "Email: %s\n" .
            "Phone: %s\n" .
            "Booking Date: %s\n" .
            "Seats: %s\n" .
            "Total Price: %d\n" .
            "Product Name: %s\n",
            $this->getId(),
            $this->getName(),
            $this->getEmail(),
            $this->getPhone(),
            $this->getBookingDate(),
            $this->getSeats(),
            $this->getTotalPrice(),
            $this->getProductName()
        );
    }

}