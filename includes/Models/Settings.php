<?php

namespace ONSBKS_Slots\Includes\Models;

class Settings
{
    private string $enableAutoCancel;
    private string $autoCancelPeriod;
    private string $payNowButtonText;
    private string $bookingOrderPaidStatuses;

    private array $data = [
        'enableAutoCancel' => true,
        'autoCancelPeriod' => 3600,
        'payNowButtonText' => 'Pay Now',
        'bookingOrderPaidStatuses' => 'Completed',
    ];

    public function __construct( $data = null )
    {
        if($data instanceof Settings){
            $this->setEnableAutoCancel( $data->getEnableAutoCancel() );
            $this->setAutoCancelPeriod( $data->getAutoCancelPeriod() );
            $this->setPayNowButtonText( $data->getPayNowButtonText() );
            $this->setBookingOrderPaidStatuses( $data->getBookingOrderPaidStatuses() );
        }
        else {
            if($data == null) {
                $data = $this->data;
            }

            $this->setEnableAutoCancel( $data['enableAutoCancel'] );
            $this->setAutoCancelPeriod( $data['autoCancelPeriod'] );
            $this->setPayNowButtonText( $data['payNowButtonText'] );
            $this->setBookingOrderPaidStatuses( $data['bookingOrderPaidStatuses'] );
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
    public function getEnableAutoCancel(): string
    {
        return $this->enableAutoCancel;
    }

    /**
     * @param string $enableAutoCancel
     */
    public function setEnableAutoCancel(string $enableAutoCancel): void
    {
        $this->data['enableAutoCancel'] = $enableAutoCancel;
        $this->enableAutoCancel = $enableAutoCancel;
    }

    /**
     * @return string
     */
    public function getAutoCancelPeriod(): string
    {
        return $this->autoCancelPeriod;
    }

    /**
     * @param int $autoCancelPeriod
     */
    public function setAutoCancelPeriod(int $autoCancelPeriod): void
    {
        $this->data['autoCancelPeriod'] = $autoCancelPeriod;
        $this->autoCancelPeriod = $autoCancelPeriod;
    }

    /**
     * @return string
     */
    public function getPayNowButtonText(): string
    {
        return $this->payNowButtonText;
    }

    /**
     * @param string $payNowButtonText
     */
    public function setPayNowButtonText(string $payNowButtonText): void
    {
        $this->data['payNowButtonText'] = $payNowButtonText;
        $this->payNowButtonText = $payNowButtonText;
    }


    /**
     * @return string
     */
    public function getBookingOrderPaidStatuses(): string
    {
        return $this->bookingOrderPaidStatuses;
    }

    /**
     * @param string $bookingOrderPaidStatuses
     */
    public function setBookingOrderPaidStatuses(string $bookingOrderPaidStatuses): void
    {
        $this->data['bookingOrderPaidStatuses'] = $bookingOrderPaidStatuses;
        $this->bookingOrderPaidStatuses = $bookingOrderPaidStatuses;
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
