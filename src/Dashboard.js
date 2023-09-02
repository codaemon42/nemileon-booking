import { Col, Divider, Row } from 'antd'
import React from 'react'
import BarChart from './components/charts/BarChart'

const Dashboard = (props) => {
  return (
    <div  {...props}>
        <Divider orientation="left" >Dashboard</Divider>
        <br />
        <Row>
            <Col span={12}>
                <BarChart />
            </Col>
            <Col span={12}>
                <BarChart />
            </Col>
        </Row>
    </div>
  )
}

export default Dashboard