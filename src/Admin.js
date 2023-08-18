import { Col, Row } from 'antd';
import React from 'react';
import SlotBuilderSteps from './components/slots/SlotBuilderSteps';



const Admin = (props) => {
  return (
    <div {...props}>
        <Row>
            <Col span={24} xs={24} xl={24}>
                <SlotBuilderSteps stepStyle={{maxWidth: 800, margin: 'auto'}} />
            </Col>
        </Row>
    </div>
  )
}

export default Admin