<?php
namespace ONSBKS_Slots\Includes\Models;


class Slot
{
    private string $gutter;
    private string $vGutter;
    private array $rows;
    private int $allowedBookingPerPerson;
    private int $total;

    private array $data = [];

    public function __construct( $data = null )
    {
        $this->setData([
            'gutter' => 8,
            'vGutter' => 8,
            'rows' => SlotRow::List(),
            'allowedBookingPerPerson' => 100,
            'total' => 0
        ]);

        if($data == null) $data = $this->data;
        $this->setGutter( $data['gutter'] );
        $this->setVGutter( $data['vGutter'] );
        $this->setRows( SlotRow::List( $data['rows'] ) );
        $this->setAllowedBookingPerPerson( $data['allowedBookingPerPerson'] );
        $this->setTotal( $data['total'] );
    }


    /**
     * @param array $initialValue
     * @return Slot[]
     */
    public static function List(array $initialValue = []): array
    {
        $arr = [new self()];
        array_shift($arr);
        if(count($initialValue)){
            foreach ($initialValue as $iv){
                array_push($arr, new self($iv));
            }
        }
        return $arr;
    }

    /**
     * @return string
     */
    public function getGutter(): string
    {
        return $this->gutter;
    }

    /**
     * @param string $gutter
     */
    public function setGutter(string $gutter): void
    {
        $this->gutter = $gutter;
    }

    /**
     * @return string
     */
    public function getVGutter(): string
    {
        return $this->vGutter;
    }

    /**
     * @param string $vGutter
     */
    public function setVGutter(string $vGutter): void
    {
        $this->vGutter = $vGutter;
    }

    /**
     * @return SlotRow[]
     */
    public function getRows(): array
    {
        return $this->rows;
    }

    /**
     * @param array $rows
     */
    public function setRows(array $rows): void
    {
        $this->rows = SlotRow::List($rows);
    }

    /**
     * @return int
     */
    public function getAllowedBookingPerPerson(): int
    {
        return $this->allowedBookingPerPerson;
    }

    /**
     * @param int $allowedBookingPerPerson
     */
    public function setAllowedBookingPerPerson(int $allowedBookingPerPerson): void
    {
        $this->allowedBookingPerPerson = $allowedBookingPerPerson;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal(int $total): void
    {
        $this->total = $total;
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