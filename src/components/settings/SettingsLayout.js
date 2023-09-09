import { useState, useEffect } from 'react';
import { BarChartOutlined, BgColorsOutlined, CustomerServiceOutlined, MessageOutlined, SearchOutlined, SettingOutlined, SyncOutlined, UploadOutlined, UserOutlined, VideoCameraOutlined, WhatsAppOutlined } from '@ant-design/icons';
import { Button, Divider, FloatButton, Layout, Menu, theme } from 'antd';
import SettingsContent from './SettingsContent';
import ContactSettings from './ContactSettings';
import AnalyticsSettings from './AnalyticsSettings';
import AdvanceSearchSettings from './AdvanceSearchSettings';
import ColorSettings from './ColorSettings';
const { Content, Footer, Sider } = Layout;


const items = [
    {
        key: '',
        label: '',
        showButton: false
    },            {
        key: 'Basic',
        icon: <SettingOutlined />,
        label: 'Basic',
        showButton: true
    },
    {
        key: 'Colors',
        icon: <BgColorsOutlined />,
        label: 'Colors',
        showButton: false
    },
    {
        key: 'Analytics',
        icon: <BarChartOutlined />,
        label: 'Analytics',
        showButton: false
    },
    {
        key: 'Advance Search',
        icon: <SearchOutlined />,
        label: 'Advance Search',
        showButton: false
    },
    {
        key: 'Contact Author',
        icon: <UserOutlined />,
        label: 'Contact Author',
        showButton: false
    },
  ]

const SettingsLayout = () => {
  const {
    token: { colorBgContainer },
  } = theme.useToken();

  const [selectedKeys, setSelectedKeys] = useState(['Basic']);
  const [selectedItem, setSelectedItem] = useState(null);

  useEffect(() => {
    setSelectedItem(items.find(i=>i.key === selectedKeys[0]))
  }, [selectedKeys])
  

  return (
    <Layout>
      <Sider
        breakpoint="lg"
        collapsedWidth="0"
        onBreakpoint={(broken) => {
          console.log(broken);
        }}
        onCollapse={(collapsed, type) => {
          console.log(collapsed, type);
        }}
      >
        <div className="demo-logo-vertical" />
        <Menu
          theme="dark"
          mode="inline"
          defaultSelectedKeys={['0']}
          selectedKeys={selectedKeys}
          items={items}
          onSelect={(selectedMenu)=> setSelectedKeys(selectedMenu.selectedKeys)}
        />
      </Sider>
      <Layout>
        <Content
          style={{
            margin: '24px 16px 0',
          }}
        >
          <Divider orientation='right'>
            {selectedKeys && selectedKeys[0]}
          </Divider>
          <div
            style={{
              padding: 24,
              minHeight: 360,
            //   background: colorBgContainer,
            }}
          >
              {
                selectedKeys.includes('Basic') ? <SettingsContent /> : <></>
              }
              {
                selectedKeys.includes('Contact Author') ? <ContactSettings  /> : <></>
              }
              
              {
                selectedKeys.includes('Advance Search') ? <AdvanceSearchSettings  /> : <></>
              }
              
              {
                selectedKeys.includes('Analytics') ? <AnalyticsSettings  /> : <></>
              }

              {
                selectedKeys.includes('Colors') ? <ColorSettings  /> : <></>
              }

          </div>
          <FloatButton icon={<WhatsAppOutlined />} type="primary" tooltip={<>Contact in whatsapp</>} style={{ right: 20 }} />
        </Content>
        <Footer
          style={{
            textAlign: 'center',
          }}
        >
          {/* Nemileon Bookings ©2023 Created by Naim-Ul-Hassan */}
          {/* {
            selectedItem && selectedItem.showButton ?
            <Button 
              icon={<SyncOutlined />}
              block
              type='primary'
              style={{'max-width': 400}}
            >
              Save {selectedKeys && selectedKeys[0]}
            </Button> : <></>
          } */}
        </Footer>
      </Layout>
    </Layout>
  );
};
export default SettingsLayout;