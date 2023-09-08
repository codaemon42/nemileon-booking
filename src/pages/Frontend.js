import { CalendarFilled, CalendarOutlined, CustomerServiceOutlined } from '@ant-design/icons';
import { Divider, FloatButton, Radio, Space } from 'antd';
import React, { useState } from 'react';
import BookingsTable from '../components/bookings/BookingsTable';
import SlotBooking from '../components/slots/SlotBooking';


const Frontend = () => {
  const [type, setType] = useState("SlotBuilder");

  return (
    <Space style={{width: '100%'}} size="large" direction="vertical">
        <Divider orientation="center" >
          <Radio.Group buttonStyle="solid" value={type} onChange={(e) => setType(e.target.value)}>
            <Radio.Button icon={<CalendarOutlined />} value="SlotBuilder">New Booking</Radio.Button>
            <Radio.Button icon={<CalendarFilled />} value="BookingsTable">My Bookings</Radio.Button>
          </Radio.Group>
        </Divider>
        {
          type === "SlotBuilder"
          ? <SlotBooking stepStyle={{maxWidth: 800, margin: 'auto', marginBottom: 35}} />
          : type === "BookingsTable" ? <BookingsTable />
          : <></>
        }
    </Space>
  )
}

export default Frontend