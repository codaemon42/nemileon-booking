import { CopyOutlined, DeleteOutlined, EditOutlined, MonitorOutlined, PlusOutlined } from '@ant-design/icons';
import { Button, Divider, Space, Table, Tooltip } from 'antd';
import {useEffect, useState} from 'react';
import { SlotTemplateType } from './components/slots/types/SlotTemplateType.type';
import { alphabeticSort } from './helper/alphabeticSort';
import { countAvailableSlots } from './helper/countAvailableSlots';
import { SlotTemplateApi } from './http/SlotTemplateApi';


const SlotTemplate = (props) => {

    const [Templates, setTemplates] = useState(SlotTemplateType.List([]));
    const [TableLoading, setTableLoading] = useState(false);

    useEffect(() => {
      setTableLoading(true);
      SlotTemplateApi.getSlotTemplates().then(slots => {
          console.log({slots});
          if(slots.success) setTemplates(slots.result);
      })
      .catch(err => console.log({err}))
      .finally(()=> setTableLoading(false))
    
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
            title: "Name of the Template",
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
          sorter: (a, b) => a.key - b.key
        },
        {
          title: "Available Slots",
          key: "availableSlots",
          render: (_, record, index) => (
              <div> {countAvailableSlots(record)}</div>
          ),
          sorter: (a, b) => countAvailableSlots(a) - countAvailableSlots(b)
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
                <Tooltip placement="bottom" title='Duplicate'>
                  <Button  onClick={() => handleDuplicate(record, index)} type="default" icon={<CopyOutlined />}></Button>
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

    // DUPLICATE BUTTON
    const handleDuplicate = async (record = new SlotTemplateType(), index) => {
      setTableLoading(true);
      const data = {...record};
      data.name = `${data.name}-copy`;
      console.log({data})
      const newSlotTemplateRes = await SlotTemplateApi.createSlotTemplate(data);
      console.log({newSlotTemplateRes})
      if(newSlotTemplateRes.success) setTemplates(prev => [newSlotTemplateRes.result, ...prev]);
      setTableLoading(false);
    }
    
  return (
    <div {...props}>
        <Divider orientation="left" plain>
          <Space>
            <h3>Saved Slot Templates</h3> 
            <Button type='primary' size='small' style={{fontSize: 11}} icon={<PlusOutlined />}>ADD NEW</Button>
          </Space> 
        </Divider>
        <Table
            loading={TableLoading}
            columns={columns}
            dataSource={Templates}
            pagination={{ pageSize: 6 }}
        />
     </div>
  )
}

export default SlotTemplate