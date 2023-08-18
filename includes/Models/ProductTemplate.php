<?php
namespace ONSBKS_Slots\Includes\Models;


class ProductTemplate
{
    private int $id;
    private int $product_id;
    private string $key;
    private Slot $template;

    private array $data = [];

    public function __construct( $data = null )
    {
        $this->setData([
            'id' => 0,
            'product_id' => 0,
            'key' => '',
            'template' => new Slot()
        ]);

        if($data == null) $data = $this->data;
        $this->setId( $data['id'] );
        $this->setProductId( $data['product_id'] );
        $this->setKey( $data['key'] );
        $this->setTemplate( new Slot($data['template']) );
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
        $this->id = $id;
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
        $this->product_id = $product_id;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * @return Slot
     */
    public function getTemplate(): Slot
    {
        return $this->template;
    }

    /**
     * @param Slot $template
     */
    public function setTemplate(Slot $template): void
    {
        $this->template = $template;
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