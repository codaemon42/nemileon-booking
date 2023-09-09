import { ArrowLeftOutlined, CloudUploadOutlined, CopyOutlined, DeleteOutlined, EditOutlined, MonitorOutlined, PlusOutlined } from '@ant-design/icons';
import { Button, Divider, message, Modal, Popconfirm, Space, Table, Tooltip } from 'antd';
import {useEffect, useState} from 'react';
import SlotBuilder from '../components/slots/SlotBuilder';
import SlotPlotter from '../components/slots/SlotPlotter';
import { Slot } from '../components/slots/types/Slot.type';
import { SlotCol } from '../components/slots/types/SlotCol.type';
import { SlotTemplateType } from '../components/slots/types/SlotTemplateType.type';
import { alphabeticSort } from '../helper/alphabeticSort';
import { countAvailableSlots } from '../helper/countAvailableSlots';
import { SlotTemplateApi } from '../http/SlotTemplateApi';


const SlotTemplate = ({...props}) => {

    const [Templates, setTemplates] = useState(SlotTemplateType.List([]));
    const [SelectedTemplate, setSelectedTemplate] = useState(new SlotTemplateType());
    const [TableLoading, setTableLoading] = useState(false);
    const [OpenPreviewModal, setOpenPreviewModal] = useState(false);
    const [OpenEditModal, setOpenEditModal] = useState(false);
    const [LoadingEditModal, setLoadingEditModal] = useState(false);
    const [OpenAddScreen, setOpenAddScreen] = useState(false);
    const [LoadingAddScreen, setLoadingAddScreen] = useState(false);
    message.config({top: 50});

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
          title: "Max BookingsEntity",
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
                  <Button onClick={() => handlePreview(record, index)} type="default" icon={<MonitorOutlined />}></Button>
                </Tooltip>
                <Tooltip placement="bottom" title='Edit'>
                  <Button onClick={() => handleEdit(record, index)} type="default" icon={<EditOutlined />}></Button>
                </Tooltip>
                <Tooltip placement="bottom" title='Duplicate'>
                  <Button  onClick={() => handleDuplicate(record, index)} type="default" icon={<CopyOutlined />}></Button>
                </Tooltip>
                <Tooltip placement="rightBottom" title='Delete'>
                  <Popconfirm
                    placement='topLeft' 
                    title="Delete the template"
                    description="Are you sure to delete this template ?"
                    okText="DELETE"
                    cancelText="CANCEL"
                    okButtonProps={{danger: true}}
                    onConfirm={() => handleDelete(record, index)}
                  >
                    <Button type="default" icon={<DeleteOutlined />}></Button>
                  </Popconfirm>
                </Tooltip>
              </Space>
            ),
        },
    ];

    const handleDelete = async (record, index) => {
      console.log({record, index})
      setTableLoading(true);
      const slotTemplateDeleteRes = await SlotTemplateApi.deleteSlotTemplates(record.id);
      setTableLoading(false);
      if(slotTemplateDeleteRes.success && slotTemplateDeleteRes.result) {
        setTemplates(prev => prev.filter(t => t.id !== record.id));
      }
    }

    const handlePreview = (record, index) => {
      console.log({record, index});
      setSelectedTemplate(record);
      setOpenPreviewModal(true);
    }

    const handleEdit = (record, index) => {
      console.log({record, index})
      setSelectedTemplate(record);
      setOpenEditModal(true);
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

    const onSlotChange = (slot) => {
      // console.log({slot});
      const newSelectedTemplate = {...SelectedTemplate}
      newSelectedTemplate.template = new Slot(slot);
      newSelectedTemplate.template.rows[0] && console.log({newSelectedTemplates: new SlotCol(newSelectedTemplate.template.rows[0].cols[0])})
      setSelectedTemplate(newSelectedTemplate);
    }

    const onNameChange = (name) => {
      console.log({name});
      const newSelectedTemplate = {...SelectedTemplate}
      newSelectedTemplate.name = name;
      setSelectedTemplate(newSelectedTemplate);
    }

    const saveEditData = async () => {
      setLoadingEditModal(true);
      message.loading('saving template...');
      const slotTemplateRes = await SlotTemplateApi.updateSlotTemplate(SelectedTemplate);
      message.destroy();
      setLoadingEditModal(false);
      if(slotTemplateRes.success){
        message.success('Successfully updated');
        const temInd = Templates.findIndex(t => t.id === SelectedTemplate.id);
        const newTemplates = [...Templates];
        newTemplates[temInd] = {...SelectedTemplate};
        setTemplates(newTemplates);
      } else {
        message.error('Something went wrong');
      }
    }

    const saveAddData = async () => {
      setLoadingAddScreen(true);
      message.loading('saving template...');
      const slotTemplateRes = await SlotTemplateApi.createSlotTemplate(SelectedTemplate);
      message.destroy();
      setLoadingAddScreen(false);
      if(slotTemplateRes.success){
        message.success('Successfully created');
        const newTemplates = [slotTemplateRes.result, ...Templates];
        setTemplates(newTemplates);
      } else {
        message.error('Something went wrong');
      }
    }
    
  return (
    <div {...props}>
        <div style={{display: OpenAddScreen ? 'none' : 'block'}}>
          <div style={{display: OpenEditModal ? 'none' : 'block'}}>
            <Divider orientation="left" plain>
              <Space>
                <h3>Saved Slot Templates</h3> 
                <Button onClick={()=> setOpenAddScreen(true)} type='primary' size='small' style={{fontSize: 11}} icon={<PlusOutlined />}>ADD NEW</Button>
              </Space> 
            </Divider>
            <Table
                loading={TableLoading}
                columns={columns}
                dataSource={Templates}
                pagination={{ pageSize: 6 }}
            />
            <Modal 
              title={`Preview Template : ${SelectedTemplate.name}`}
              open={OpenPreviewModal} 
              onOk={()=>setOpenPreviewModal(false)} 
              onCancel={()=>setOpenPreviewModal(false)} 
              width={window.innerWidth*0.6}
              >
              <Divider orientation="left" plain></Divider>
              <SlotPlotter key={SelectedTemplate.key} defaultSlot={SelectedTemplate.template} />
            </Modal>
          </div>
          <div style={{display: OpenEditModal ? 'block' : 'none'}} key={SelectedTemplate?.id || 0}>
            <Divider orientation="left" plain>
              <Space>
                <Button onClick={()=> setOpenEditModal(false)} type='primary' size='small' style={{fontSize: 11}} icon={<ArrowLeftOutlined />}>BACK</Button>
                <h3> SLOT TEMPLATE: {SelectedTemplate.name}</h3> 
              </Space> 
            </Divider>
            <SlotBuilder key={3} initialTemplate={SelectedTemplate} onSlotChange={onSlotChange} onNameChange={onNameChange} />
            <Divider orientation="right" plain>
              <Button loading={LoadingEditModal} onClick={()=> saveEditData()} className='onsbks-success' type='primary' icon={<CloudUploadOutlined />}>SAVE</Button>
            </Divider>
          </div>
        </div>
        <div style={{display: OpenAddScreen ? 'block' : 'none'}}>
            <Divider orientation="left" plain>
              <Space>
              <Button onClick={()=> setOpenAddScreen(false)} type='primary' size='small' style={{fontSize: 11}} icon={<ArrowLeftOutlined />}>BACK</Button>
                <h3> Add New Template</h3>
              </Space> 
            </Divider>
            <SlotBuilder key={3} initialTemplate={SelectedTemplate} onSlotChange={onSlotChange} onNameChange={onNameChange} />
            <Divider orientation="right" plain>
              <Button loading={LoadingAddScreen} onClick={()=> saveAddData()} className='onsbks-success' type='primary' icon={<CloudUploadOutlined />}>SAVE</Button>
            </Divider>
        </div>
     </div>
  )
}

export default SlotTemplate