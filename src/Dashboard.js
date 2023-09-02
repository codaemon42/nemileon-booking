import { Col, Divider, Row } from 'antd'
import React from 'react'
import BarChart from './components/charts/BarChart'
import BarStackChart from './components/charts/BarStackChart'

const Dashboard = (props) => {
  return (
    <div  {...props}>
        <Divider orientation="left" > <h3> Dashboard </h3></Divider>
        <br />
        <Row>
            <Col span={11} offset={1}>
                <BarChart />
            </Col>
            <Col span={10}  offset={1}>
                <BarStackChart />
            </Col>
        </Row>
    </div>
  )
}

export default Dashboard