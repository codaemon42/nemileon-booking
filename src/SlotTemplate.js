import { CheckOutlined, DeleteOutlined, EditOutlined, MonitorOutlined } from '@ant-design/icons';
import { Button, Divider, Space, Table, Tooltip } from 'antd';
import {useEffect, useState} from 'react';
import { Slot } from './components/slots/types/Slot.type';
import { SlotTemplateType } from './components/slots/types/SlotTemplateType.type';
import { SlotTemplateApi } from './http/SlotTemplateApi';


const SlotTemplate = (props) => {

    const [Templates, setTemplates] = useState(SlotTemplateType.List([]));

    useEffect(() => {
      SlotTemplateApi.getSlotTemplates().then(slots => {
          console.log({slots});
          if(slots.success) setTemplates(slots.result);
      }).catch(err => console.log({err}))
    
    }, [])
    
    const nameFilterRender = (data=[]) => {
      let r = []
      data.map(d => {
        r.push({text: d.name, value: d.name})
      })
      return r;
    }
    const columns = [
        {
            title: "Name",
            dataIndex: "name",
            key: "name",
            filters: nameFilterRender(Templates),
            render: (text) => <a>{text}</a>,
            onFilter: (value, record) => record.name.indexOf(value) === 0,
            filterSearch: true,
            sorter: (a, b) => alphabeticSort(a, b, "name"),
        },
        {
          title: "Max Booking",
          key: "allowedBookingPerPerson",
          render: (_, record, index) => (
              <div> {record.template.allowedBookingPerPerson}</div>
          ),
        },
        {
          title: "Available Slots",
          key: "availableSlots",
          render: (_, record, index) => (
              <div> {countAvailableSlots(record)}</div>
          ),
        },
        {
          title: "Dimension",
          key: "dimension",
          render: (_, record, index) => (
              <div> {record.template.rows.length}x{record.template.rows[0].cols.length}</div>
          ),
        },
        {
            title: "Action",
            key: "action",
            render: (_, record, index) => (
              <Space>
                <Tooltip placement="leftBottom" title='View'>
                  <Button onClick={() => handleAction(record, index)} type="default" icon={<MonitorOutlined />}></Button>
                </Tooltip>
                <Tooltip placement="bottom" title='Edit'>
                  <Button onClick={() => handleAction(record, index)} type="default" icon={<EditOutlined />}></Button>
                </Tooltip>
                <Tooltip placement="rightBottom" title='Delete'>
                  <Button  onClick={() => handleAction(record, index)} type="default" icon={<DeleteOutlined />}></Button>
                </Tooltip>
              </Space>
            ),
        },
    ];

    const handleAction = (record, index) => {
      console.log({record, index})
    }

    const countAvailableSlots = (slotTemplate=new SlotTemplateType()) => {
      let availableSlots = 0;
      slotTemplate.template.rows.forEach(row => {
        row.cols.forEach(col => {
          if(col.show){
            availableSlots += col.available_slots;
          }
        })
      })
      return availableSlots;
    }
    
  return (
    <div {...props}>
        <Divider orientation="left" plain>
          <h3>Saved Slot Templates</h3> 
        </Divider>
        <Table
            columns={columns}
            dataSource={Templates}
            pagination={{ pageSize: 2 }}
        />
    </div>
  )
}

export default SlotTemplate