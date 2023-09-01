import { CloseOutlined, ExpandOutlined, ShoppingOutlined } from '@ant-design/icons';
import { Button, Space, Tooltip, Popconfirm } from 'antd'
import BookingStatus from './BookingStatus';


const BookingButtonGroup = ({record, index, onView=()=>{}, onBook=()=>{}, onCancel=()=>{}}) => {
    return (
        <Space>
            <Tooltip placement="leftBottom" title='View'>
                <Button onClick={() => onView(record, index)} type="primary" icon={<ExpandOutlined />} /> 
            </Tooltip>
            
            {
                record.status === BookingStatus.PENDING_PAYMENT ? 
                <Tooltip placement="bottom" title='Pay Now'>
                    <Button className='onsbks-success' onClick={() => onBook(record, index)} type="primary" icon={<ShoppingOutlined />}/>
                </Tooltip>
                : 
                <Tooltip placement="bottom" title='paid'>
                    <Button disabled  icon={<ShoppingOutlined />}/>
                </Tooltip>
            }
            <Popconfirm
                placement="topRight"
                title='Do you really want to cancel the booking ?'
                description=''
                onConfirm={()=> onCancel(record, index)}
                okText="Yes"
                cancelText="No"
            >
                {
                    record.status === BookingStatus.PENDING_PAYMENT
                    ?
                    <Tooltip placement="right" title='Cancel Booking'>
                        <Button danger type="primary" icon={<CloseOutlined />} /> 
                    </Tooltip>
                    :
                    <Tooltip placement="right" title='Cancel Booking'>
                        <Button disabled icon={<CloseOutlined />} /> 
                    </Tooltip>
                }
            </Popconfirm>
            
            
        </Space>
    )
}

export default BookingButtonGroup