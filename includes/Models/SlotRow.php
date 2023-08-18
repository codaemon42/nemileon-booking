<?php
namespace ONSBKS_Slots\Includes\Models;


class SlotRow
{
    private string $header;
    private string $description;
    private bool $showToolTip;
    private array $cols;

    private array $data = [];

    public function __construct( $data = null )
    {
        $this->setData([
            'header' => '',
            'description' => '',
            'showToolTip' => false,
            'cols' => SlotCol::List()
        ]);

        if($data == null) $data = $this->data;
        $this->setHeader( $data['header'] );
        $this->setDescription( $data['description'] );
        $this->setShowToolTip( $data['showToolTip'] );
        $this->setCols( SlotCol::List($data['cols']) );
    }


    /**
     * @param array $initialValue
     * @return SlotRow[]
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
    public function getHeader(): string
    {
        return $this->header;
    }

    /**
     * @param string $header
     */
    public function setHeader(string $header): void
    {
        $this->header = $header;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return bool
     */
    public function isShowToolTip(): bool
    {
        return $this->showToolTip;
    }

    /**
     * @param bool $showToolTip
     */
    public function setShowToolTip(bool $showToolTip): void
    {
        $this->showToolTip = $showToolTip;
    }

    /**
     * @return SlotCol[]
     */
    public function getCols(): array
    {
        return $this->cols;
    }

    /**
     * @param array $cols
     */
    public function setCols(array $cols): void
    {
        $this->cols = $cols;
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