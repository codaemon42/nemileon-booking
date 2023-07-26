import { CloudUploadOutlined, LeftOutlined } from '@ant-design/icons'
import { Button, Col, Divider, message, Row, Select, Space } from 'antd'
import { useState, useEffect } from 'react'
import { SlotTemplateApi } from '../../http/SlotTemplateApi'
import SlotBuilder from './SlotBuilder'
import SlotPlotter from './SlotPlotter'
import { SlotTemplateType } from './types/SlotTemplateType.type'


const SlotTemplateSelector = ({onSelectSlotTemplate}) => {
    message.config({top: 50});

    const [SlotTemplates, setSlotTemplates] = useState(SlotTemplateType.List([]));
    const [SelectedSlotTemplate, setSelectedSlotTemplate] = useState(new SlotTemplateType());

    const [OpenAddSlotTemplate, setOpenAddSlotTemplate] = useState(false);
    const [LoadingSaveButton, setLoadingSaveButton] = useState(false);


    useEffect(() => {
        getSlotTemplates();
    }, [])

    useEffect(()=>{
        if(SelectedSlotTemplate.id) onSelectSlotTemplate(SelectedSlotTemplate)
    },[SelectedSlotTemplate])
    

    const getSlotTemplates = async () => {
        const templateListRes = await SlotTemplateApi.getSlotTemplates();
        if(templateListRes.success){
            setSlotTemplates(templateListRes.result);
        }
    }

    const slotTemplateToOptionConverter = () => {
        return SlotTemplates.map(st => ({ label: st.name, value: st.id} ))
    }

    const selectedSlotTemplateToDefaultConverter = () => {
        return SelectedSlotTemplate.id ? { label: SelectedSlotTemplate.name, value: SelectedSlotTemplate.id } : null;
    }

    const onChange = (value) => {
        console.log(`selected ${value}`);
        setSelectedSlotTemplate(SlotTemplates.find(st => st.id == value))
    };

    const onSearch = (value) => {
        console.log('search:', value);
    };

    
    const onNameChange = (name) => {
        console.log('name:', name);
        const newSelectedSlotTemplate = {...SelectedSlotTemplate};
        newSelectedSlotTemplate.name = name;
        setSelectedSlotTemplate(newSelectedSlotTemplate);
    };

    const onSlotChange = (slot) => {
        console.log('slot:', slot);
        const newSelectedSlotTemplate = {...SelectedSlotTemplate};
        newSelectedSlotTemplate.template = slot;
        setSelectedSlotTemplate(newSelectedSlotTemplate);
    };


    const saveAddData = async () => {
        setLoadingSaveButton(true);
        const slotTemplateRes = await SlotTemplateApi.createSlotTemplate(SelectedSlotTemplate);
        setLoadingSaveButton(false);
        if(slotTemplateRes.success) {
            setSelectedSlotTemplate(slotTemplateRes.result);
            setSlotTemplates(prev => [slotTemplateRes.result, ...prev]);
            message.success(slotTemplateRes.message)
        } else {
            message.error(slotTemplateRes.message)
        }
        
        setOpenAddSlotTemplate(false);
    }




  return (
    <>
        <Row style={{display: OpenAddSlotTemplate ? 'none' : 'flex'}}>
            <Col span={6}>
                <Space direction='vertical' style={{minWidth: '100%'}}>
                    <h4> Search Template </h4> 
                    <Select
                        key={SelectedSlotTemplate.key}
                        defaultValue={selectedSlotTemplateToDefaultConverter()}
                        showSearch
                        placeholder="Select a template"
                        size='large'
                        style={{minWidth: '100%'}}
                        optionFilterProp="children"
                        onChange={onChange}
                        onSearch={onSearch}
                        filterOption={(input, option) =>
                            (option?.label ?? '').toLowerCase().includes(input.toLowerCase())
                        }
                        options={slotTemplateToOptionConverter()}
                    />

                    <div style={{paddingTop: 50}}>
                        <span>Want to add new template here ? </span>
                        <Button onClick={()=>setOpenAddSlotTemplate(true)} className='onsbks-success' type='primary' size='small' style={{fontSize: 12}}>
                            ADD NEW
                        </Button>
                    </div>
                </Space>
            </Col>
            <Col span={16} offset={1}>
                <Space direction='vertical' style={{minWidth: '100%'}} >
                    <h4> Preview Template </h4> 
                    <SlotPlotter key={SelectedSlotTemplate.key} defaultSlot={SelectedSlotTemplate.template} />
                </Space>
            </Col>
        </Row>
        <Row style={{display: OpenAddSlotTemplate ? 'flex' : 'none'}}>
            <Col span={24}>
                <SlotBuilder key={SelectedSlotTemplate.key} initialTemplate={SelectedSlotTemplate} onSlotChange={onSlotChange} onNameChange={onNameChange}  />
                <Divider>
                    <Space>
                        <Button onClick={()=> setOpenAddSlotTemplate(false)} type='default' icon={<LeftOutlined />}>BACK </Button>
                        <Button loading={LoadingSaveButton} onClick={()=> saveAddData()} className='onsbks-success' type='primary' icon={<CloudUploadOutlined />}>CREATE TEMPLATE</Button>
                    </Space>
                </Divider>
            </Col>
        </Row>
    </>
  )
}

export default SlotTemplateSelector