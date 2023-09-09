import { FireFilled, SyncOutlined } from '@ant-design/icons'
import { Button, Col, Row } from 'antd'
import React from 'react'

const SettingsInputWrapper = ({title, isPro = false, loading = false, onClick, children}) => {
    return (
        <Row style={{marginBottom: 20}} onClick={onClick}>
            <Col span={6} style={{alignSelf: 'center'}}>
                {title}
            </Col>
            <Col span={10} offset={2} style={{alignSelf: 'center'}}>
                <Row>
                    <Col>
                        {children}
                    </Col>
                    <Col style={{alignSelf: 'center'}} offset={1}>
                        {
                            loading ? 
                            <SyncOutlined spin />
                            :
                            <></>
                        }
                    </Col>
                    <Col>
                        {
                            isPro ?
                            <Button 
                                style={{alignSelf: 'center', marginLeft: 20}} 
                                ghost 
                                type='link' 
                                icon={<FireFilled />} 
                                danger
                            >
                                PRO VERSION
                            </Button> : <></>
                        }
                    </Col>
                </Row>
            </Col>
            {/* <Col span={2} style={{alignSelf: 'center'}}>
                <Button icon={<FireFilled />} danger>PRO version</Button>
            </Col> */}
        </Row>
    )
}

export default SettingsInputWrapper