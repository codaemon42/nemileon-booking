<?php

namespace ONSBKS_Slots\Includes\Models;


class SlotCol
{

    private string $product_id;
    private string $content;
    private bool $show;
    private int $available_slots;
    private bool $checked;
    private int $booked;
    private string $expires_in;
    private int $book;

    private array $data = [
        'product_id' => '0',
        'content' => 'Content',
        'show' => false,
        'available_slots' => 0,
        'checked' => false,
        'booked' => 0,
        'expires_in' => '',
        'book' => 0,
    ];

    public function __construct( $data = null )
    {
        if($data instanceof SlotCol){
            $this->setProductId( $data->getProductId() );
            $this->setContent( $data->getContent() );
            $this->setShow( $data->getShow() );
            $this->setAvailableSlots( $data->getAvailableSlots() );
            $this->setChecked( $data->getChecked() );
            $this->setBooked( $data->getBooked() );
            $this->setExpiresIn( $data->getExpiresIn() );
            $this->setBook( $data->getBook() );
        }
        else {
            if($data == null) $data = $this->data;
            $this->setProductId( $data['product_id'] );
            $this->setContent( $data['content'] );
            $this->setShow( $data['show'] );
            $this->setAvailableSlots( $data['available_slots'] );
            $this->setChecked( $data['checked'] );
            $this->setBooked( $data['booked'] );
            $this->setExpiresIn( $data['expires_in'] );
            $this->setBook( $data['book'] );
        }

    }


    /**
     * @param array $initialValue
     * @return SlotCol[]
     */
    public static function List(array $initialValue = []): array
    {
        $arr = [new self()];
        array_shift($arr);
        if(count($initialValue)){
            foreach ($initialValue as $iv){
                $arr[] = new SlotCol($iv);
            }
        }
        return $arr;
    }

    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->product_id;
    }

    /**
     * @param string $product_id
     */
    public function setProductId(string $product_id): void
    {
        $this->data['product_id'] = $product_id;
        $this->product_id = $product_id;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->data['content'] = $content;
        $this->content = $content;
    }

    /**
     * @return bool
     */
    public function getShow(): bool
    {
        return $this->show;
    }

    /**
     * @param bool $show
     */
    public function setShow(bool $show): void
    {
        $this->data['show'] = $show;
        $this->show = $show;
    }

    /**
     * @return int
     */
    public function getAvailableSlots(): int
    {
        return $this->available_slots;
    }

    /**
     * @param int $available_slots
     */
    public function setAvailableSlots(int $available_slots): void
    {
        $this->data['available_slots'] = $available_slots;
        $this->available_slots = $available_slots;
    }

    /**
     * @return bool
     */
    public function getChecked(): bool
    {
        return $this->checked;
    }

    /**
     * @param bool $checked
     */
    public function setChecked(bool $checked): void
    {
        $this->data['checked'] = $checked;
        $this->checked = $checked;
    }

    /**
     * @return int
     */
    public function getBooked(): int
    {
        return $this->booked;
    }

    /**
     * @param int $booked
     */
    public function setBooked(int $booked): void
    {
        $this->data['booked'] = $booked;
        $this->booked = $booked;
    }

    /**
     * @return string|null
     */
    public function getExpiresIn(): ?string
    {
        return $this->expires_in;
    }

    /**
     * @param string|null $expires_in
     */
    public function setExpiresIn(?string $expires_in): void
    {
        $this->data['expires_in'] = $expires_in ?: '';
        $this->expires_in = $expires_in ?: '';
    }

    /**
     * @return int
     */
    public function getBook(): int
    {
        return $this->book;
    }

    /**
     * @param int $book
     */
    public function setBook(int $book): void
    {
        $this->data['book'] = $book;
        $this->book = $book;
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
