import { CustomerServiceFilled, CustomerServiceOutlined, LinkedinOutlined, MailOutlined, WhatsAppOutlined } from '@ant-design/icons'
import { Button, List, Result, Space } from 'antd'
import React from 'react'

const ContactSettings = () => {


    return (
        <>
          <Result
                icon={<CustomerServiceFilled />}
                title="Feel free to contact for any questions"
                extra={
                    <Space size='large'>
                        <Button shape='circle' type='primary' href='mailto:naimjcc@gmail.com' target='_blank' icon={<MailOutlined  />}></Button>
                        <Button shape='circle' type='primary' href='https://wa.link/bnsc4y' target='_blank' icon={<WhatsAppOutlined />}></Button>
                        <Button shape='circle' type='primary' href='https://bd.linkedin.com/in/naim-ul-hassan-b2b907183' target='_blank' icon={<LinkedinOutlined  />}></Button>
                    </Space>
                }
            />

        </>
    )
}

export default ContactSettings