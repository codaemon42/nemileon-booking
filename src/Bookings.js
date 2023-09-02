import { PlusOutlined } from '@ant-design/icons'
import { Button, Divider, Space } from 'antd'
import React from 'react'
import BookingsTable from './components/bookings/BookingsTable'

const Bookings = (props) => {
  return (
    <div {...props}>
        <Divider orientation="left" plain>
            <Space>
                <h3>Bookings</h3> 
                <Button  type='primary' size='small' style={{fontSize: 11}} icon={<PlusOutlined />}>ADD NEW</Button>
            </Space> 
        </Divider>
        <BookingsTable />
    </div>
  )
}

export default Bookings