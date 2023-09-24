import { green, grey } from '@ant-design/colors'
import { SafetyCertificateFilled } from '@ant-design/icons'
import { Card, Col, Divider, QRCode, Row, Space, Watermark } from 'antd'
import React from 'react'
import useTicket from '../components/tickets/useTicket'

const VerifyTicket = () => {
  const { ticket, currency, siteTitle } = useTicket({verify: true});

  return (
    <Space style={{width: '100%'}} size="large" direction="vertical">
      <Card 
        
        style={{width: 820, margin: 'auto'}}
        bordered
      >
        <Watermark
          height={30}
          width={100}
          content={siteTitle}
          
        >

          <div id='nmlbookingticket' style={{padding: 20}}>
            <Divider>
            <div dangerouslySetInnerHTML={{__html: reactObj.logoUrl}}></div>
            </Divider>
            <Row >
              <Col span={8}>
                  <Space direction='vertical'>
                    <div>
                        <b>Booking No: </b> {ticket.id}
                    </div>
                    <div>
                      <b>Issuer: </b> {ticket.email}
                    </div>
                    <div>
                      <b>Item: </b> {ticket.product_name}
                    </div>
                  </Space>
              </Col>

              <Col span={8}>
                <div 
                    style={{
                      display: 'flex',
                      flexDirection: 'row',
                      justifyContent: 'center'
                    }}
                  >
                    <Space direction='vertical'>
                      <div>
                        <b>Total Seats: </b> {ticket.seats}
                      </div>
                      <div>
                        <b>Total Price: </b> {currency}{ticket.total_price}
                      </div>
                      <div>
                        <b>Booking Date: </b> {ticket.booking_date}
                      </div>
                    </Space>
                  </div>
              </Col>

              <Col span={8}>
                <div 
                  style={{
                    display: 'flex',
                    flexDirection: 'row',
                    justifyContent: 'end'
                  }}
                >
                  <div id="nmlQrCode">
                    <QRCode
                      value={window.location.href}
                      bgColor="#fff"
                      size={96}
                      style={{
                        marginBottom: 16
                      }}
                    />
                  </div>
                </div>
              </Col>
            </Row>
            <Row >
                <Col>
                    <Space>

                        <SafetyCertificateFilled style={{color: green[6]}} /> 
                        <span style={{color: grey[6]}}>This ticket is issued to {ticket.name} and verified by {siteTitle}.</span>
                    </Space>
                </Col>
            </Row>
          </div>
        </Watermark>
      </Card>
    </Space>
  )
}

export default VerifyTicket