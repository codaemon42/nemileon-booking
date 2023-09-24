import { CalendarFilled, CalendarOutlined } from '@ant-design/icons';
import { Divider, Radio, Space } from 'antd';
import React, { useContext, useState } from 'react';
import BookingsTable from '../components/bookings/BookingsTable';
import SlotBooking from '../components/slots/SlotBooking';
import { Context } from '../contexts/Context';


const Frontend = () => {
  const [type, setType] = useState("SlotBuilder");
  const { context } = useContext(Context);

  return (
    <>
    {
      parseInt(context.ticket) ?
      <Space style={{width: '100%'}} size="large" direction="vertical">
          {/* <Divider orientation="center" >
            <Button type="primary" icon={<DownloadOutlined />} >
              Ticket
            </Button>
          </Divider> */}
          {/* <Ticket />  */}
          {/* <VerifyTicket /> */}
      </Space>
      : 
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
            :  <></>
          }
      </Space>
    }
    </>
  )
}

export default Frontend