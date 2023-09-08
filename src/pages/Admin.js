import { Col, Divider, Row } from 'antd';
import React from 'react';
import SlotBuilderSteps from '../components/slots/SlotBuilderSteps';



const Admin = (props) => {
  return (
    <div {...props}>
        <Divider orientation='left' plain>
          <h3> Product Template Builder </h3>
        </Divider>
        <Row>
            <Col span={24} xs={24} xl={24}>
                <SlotBuilderSteps stepStyle={{maxWidth: 800, margin: 'auto', marginTop: 10}} />
            </Col>
        </Row>
    </div>
  )
}

export default Admin