<?php

namespace ONSBKS_Slots\Includes\Models;

class Analytics
{
    private string $xAxis;
    private string $yAxis;
    private string $type;

    private array $data = [
        'xAxis' => '0',
        'yAxis' => '',
        'type' => ''
    ];

    public function __construct($data=null)
    {
        if($data instanceof Analytics){
            $this->setXAxis( $data->getXAxis() );
            $this->setYAxis( $data->getYAxis() );
            $this->setType( $data->getType() );
        }
        else {
            if($data == null) $data = $this->data;
            $this->setXAxis( $data['xAxis'] );
            $this->setYAxis( $data['yAxis'] );
            $this->setType( $data['type'] );
        }
    }

    /**
     * @param array $initialValue
     * @return Analytics[]
     */
    public static function List(array $initialValue = []): array
    {
        $arr = [new self()];
        array_shift($arr);
        if(count($initialValue)){
            foreach ($initialValue as $iv){
                $arr[] = new Analytics($iv);
            }
        }
        return $arr;
    }

    /**
     * @return string
     */
    public function getXAxis(): string
    {
        return $this->xAxis;
    }

    /**
     * @param string $xAxis
     */
    public function setXAxis(string $xAxis = '0'): void
    {
        $this->data['xAxis'] = $xAxis;
        $this->xAxis = $xAxis;
    }

    /**
     * @return string
     */
    public function getYAxis(): string
    {
        return $this->yAxis;
    }

    /**
     * @param string $yAxis
     */
    public function setYAxis(string $yAxis = ''): void
    {
        $this->data['yAxis'] = $yAxis;
        $this->yAxis = $yAxis;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type = ''): void
    {
        $this->data['type'] = $type;
        $this->type = $type;
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