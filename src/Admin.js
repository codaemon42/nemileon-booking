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
        <Row>
            <Col span={24} xs={24} xl={24}>
                <SlotBuilderSteps />
            </Col>
        </Row>
    </div>
  )
}

export default Admin