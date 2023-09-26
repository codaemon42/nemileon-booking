import { CheckOutlined, CloseOutlined } from '@ant-design/icons'
import { Input, Select, Switch, TimePicker } from 'antd'
import * as dayjs from 'dayjs'
import SettingsInputTitle from './SettingsInputTitle'
import SettingsInputWrapper from './SettingsInputWrapper'
import { SettingsKeys } from './SettingsKeys'
import useSettings from './useSettings'

const SettingsContent = () => {

    const { 
        isLoading,
        handleSelectedInput,
        holdingTime, onChangeTime,
        orderStatuses, selectedStatuses, onChangeOrderStatus,
        enableAutoCancel, handleAutoCancel,
        payNowButtonText, onChangeInput
    } = useSettings();
    

    return (
        <>
            <SettingsInputWrapper 
                key={SettingsKeys.ENABLE_AUTO_CANCEL} 
                title={
                    <SettingsInputTitle title='Enable auto cancel booking'>
                        Enable auto cancel booking in user does not pay with a time period
                    </SettingsInputTitle>
                }
                onClick={()=>handleSelectedInput(SettingsKeys.ENABLE_AUTO_CANCEL)}
                loading={isLoading(SettingsKeys.ENABLE_AUTO_CANCEL)}
            >
                <Switch
                    checkedChildren={<CheckOutlined />}
                    unCheckedChildren={<CloseOutlined />}
                    checked={enableAutoCancel}
                    onChange={handleAutoCancel}
                />
            </SettingsInputWrapper>

            {
                enableAutoCancel ?
                <SettingsInputWrapper 
                    key={SettingsKeys.TIME_PERIOD} 
                    title={
                        <SettingsInputTitle title='Time Period'>
                            Time from booking created to make payment otherwise, the booking will be cancelled and the slot will be automatically freed up
                        </SettingsInputTitle>
                    }
                    onClick={()=>handleSelectedInput(SettingsKeys.TIME_PERIOD)}
                    loading={isLoading(SettingsKeys.TIME_PERIOD)}
                >
                    <TimePicker type='time' value={dayjs().startOf('year').add(holdingTime)} onChange={onChangeTime} />
                </SettingsInputWrapper> : <></>
            }
            
            <SettingsInputWrapper 
                key={SettingsKeys.PAY_NOW_BUTTON_TEXT} 
                title={
                    <SettingsInputTitle title='Pay Now button text'>
                        Pay Now Icon Button Tooltip text for users
                    </SettingsInputTitle>
                }
                onClick={()=>handleSelectedInput(SettingsKeys.PAY_NOW_BUTTON_TEXT)}
                loading={isLoading(SettingsKeys.PAY_NOW_BUTTON_TEXT)}
            >
                <Input value={payNowButtonText} onChange={(event)=> onChangeInput(event.target.value)} />
            </SettingsInputWrapper>

            <SettingsInputWrapper 
                key={SettingsKeys.BOOKING_ORDER_STATUS}
                title={
                    <SettingsInputTitle title='Booking paid order status'>
                        Select woocommerce order statues for which the bookings should be termed as paid
                    </SettingsInputTitle>
                }
                onClick={()=>handleSelectedInput(SettingsKeys.BOOKING_ORDER_STATUS)}
                loading={isLoading(SettingsKeys.BOOKING_ORDER_STATUS)}
            >
                <Select
                    key={Math.random()}
                    mode="multiple"
                    allowClear
                    style={{
                        width: '100%',
                        minWidth: '150px'
                    }}
                    placeholder="Please select"
                    defaultValue={selectedStatuses}
                    onChange={onChangeOrderStatus}
                    options={orderStatuses}
                />
            </SettingsInputWrapper>
        </>
    )
}

export default SettingsContent