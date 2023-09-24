import { CloudDownloadOutlined, PrinterOutlined, QrcodeOutlined } from '@ant-design/icons'
import { Card, Col, Divider, QRCode, Row, Space, Tooltip } from 'antd'
import React from 'react'
import { usePDF } from 'react-to-pdf'
import { useReactToPrint } from 'react-to-print'
import TicketSlotPlotterPreview from '../components/tickets/TicketSlotPlotterPreview'
import useTicket from '../components/tickets/useTicket'
import { downloadQRCode } from '../helper/downloadQRCode'

const Ticket = () => {
  const { ticket, currency, loading } = useTicket({verify: false});
  const { toPDF, targetRef } = usePDF({filename: 'ticket.pdf'});

  const handlePrint = useReactToPrint({
    content: () => targetRef.current,
  });

  return (
    <Space style={{width: '100%'}} size="large" direction="vertical">
      <Card 
        
        style={{width: 820, margin: 'auto'}}
        bordered
        actions={[
          <Tooltip title='Download PDF'>
            <CloudDownloadOutlined key="pdf" onClick={() => toPDF()} />
          </Tooltip>,
          <Tooltip title='Print Now'>
            <PrinterOutlined key="print" onClick={()=> handlePrint()} />
          </Tooltip>,
          <Tooltip title='Download QRCode'>
            <QrcodeOutlined key="qrcode" onClick={downloadQRCode} />
          </Tooltip>,
        ]}
      >
        {/* <Watermark
          height={30}
          width={100}
          // content='Booking Slot'
          image="https://mdn.alipayobjects.com/huamei_7uahnr/afts/img/A*lkAoRbywo0oAAAAAAAAAAAAADrJ8AQ/original"
          
        > */}

          <div id='nmlbookingticket'  ref={targetRef} style={{padding: 20}}>
            <Divider>
            <div dangerouslySetInnerHTML={{__html: reactObj.logoUrl}}></div>
            </Divider>
            <Row >
              <Col span={8}>
                  <Space direction='vertical'>
                    <div>
                      <b>Name: </b> {ticket.name}
                    </div>
                    <div>
                      <b>Email: </b> {ticket.email}
                    </div>
                    <div>
                      <b>Phone: </b> {ticket.phone}
                    </div>
                    <br />
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
                        <b>Date: </b> {ticket.booking_date}
                      </div>
                      <br />
                      <div>
                        <b>Booking No: </b> {ticket.id}
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
                      value={window.location.origin+window.location.pathname+'?verify_ticket='+ticket.id}
                      bgColor="#fff"
                      size={96}
                      style={{
                        marginBottom: 16
                      }}
                    />
                    {/* <Button type="primary" onClick={downloadQRCode}>
                      Download
                    </Button> */}
                  </div>
                </div>
              </Col>
            </Row>
            <Divider>
              
            </Divider>
            <TicketSlotPlotterPreview key={ticket.id} slot={ticket.template} loading={loading} />
            {/* <OrderSlotPlotterPreview key={ticket.id} bookingId={ticket.id} /> */}
          </div>
        {/* </Watermark> */}
      </Card>
    </Space>
  )
}

export default Ticket