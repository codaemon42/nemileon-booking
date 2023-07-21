import { Col, Divider, Row } from 'antd';
import React from 'react';
import BoxCalendar from './components/calendar/BoxCalendar';
import ProductsTable from './components/products/ProductsTable';
import SlotBuilder from './components/slots/SlotBuilder';
import SlotBuilderSteps from './components/slots/SlotBuilderSteps';
import SlotPlotter from './components/slots/SlotPlotter';



const Admin = (props) => {
  return (
    <div {...props}>
        <h2 className='app-title'>React Booking Section</h2>
        <hr />
        {/* <ProductsTable /> */}
        
        <Row>
            <Col span={24} xs={24} xl={24}>
                <SlotBuilderSteps />
            </Col>
            {/* <Col span={8} xs={22} xl={6} offset={1}>
                <Divider orientation="left">Select Date </Divider>
                <BoxCalendar />
            </Col>
            <Col span={12} xs={22} xl={14} offset={1}>
                <Divider orientation="left">Select Slots </Divider>
                <SlotBuilder />
            </Col> */}
        </Row>
    </div>
  )
}

export default Admin