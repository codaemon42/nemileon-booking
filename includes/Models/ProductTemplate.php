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
        if($data instanceof self){
            $this->setId( $data->getId() );
            $this->setProductId( $data->getProductId() );
            $this->setKey( $data->getKey() );
            $this->setTemplate( $data->getData()['template'] );
        } else {
            if($data == null) $data = $this->data;
            $this->setId( $data['id'] );
            $this->setProductId( $data['product_id'] );
            $this->setKey( $data['key'] );
            $this->setTemplate( $data['template'] );
        }
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
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey(string $key): void
    {
        $this->data['key'] = $key;
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
     * @param $template
     */
    public function setTemplate($template): void
    {
        $this->data['template'] = $template;
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


}