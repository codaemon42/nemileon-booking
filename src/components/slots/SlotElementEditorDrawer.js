import React, { useState } from 'react';
import { EditOutlined, PlusOutlined } from '@ant-design/icons';
import { Button, Checkbox, Col, DatePicker, Drawer, Form, Input, InputNumber, Row, Select, Space } from 'antd';
import { SlotCol } from './types/SlotCol.type';
const { Option } = Select;
const SlotElementEditorDrawer = ({key, style, slotCol = new SlotCol(), onFinish}) => {
  const [open, setOpen] = useState(false);
  const [form] = Form.useForm();
  const showDrawer = () => {
    setOpen(true);
  };
  const onClose = () => {
    setOpen(false);
  };

  const handleSubmit = () => {
    setOpen(false);
    form.submit();
  };


  const onSubmit = (formValues) => {
    onFinish(formValues);
  }
  return (
    <div key={key} style={style}>
      <Button key={key} type="ghost" style={{color: '#fff'}} onClick={showDrawer} icon={<EditOutlined />}>
        {/* Edit */}
      </Button>
      <Drawer
        key={key}
        title="Slot Element"
        width={720}
        onClose={onClose}
        open={open}
        bodyStyle={{
          paddingBottom: 80,
        }}
        style={{
            paddingTop: 35,
            paddingLeft: 161
        }}
        placement='left'
        extra={
          <Space>
            <Button onClick={onClose}>Cancel</Button>
            <Button onClick={handleSubmit} type="primary">
              Submit
            </Button>
          </Space>
        }
      >
        <Form
          key={key}
          form={form}
          onFinish={onSubmit}
          layout="vertical" 
          hideRequiredMark
          initialValues={{
            content: slotCol.content || null,
            content_all: false,
            available_slots: slotCol.available_slots || 1,
            available_slots_all: false,
            show: slotCol.show || true,
            show_all: false
          }}
          >
          <Row gutter={16}>
            <Col span={14}>
              <Form.Item
                name="content"
                label="Content"
                rules={[
                  {
                    required: true,
                    message: 'Please enter Content',
                  },
                ]}
              >
                <Input placeholder="Please enter Content" />
              </Form.Item>
            </Col>
            <Col span={10}>
              <Form.Item
                name="content_all"
                label=" "
                valuePropName="checked"
                rules={[
                  {
                    required: false
                  },
                ]}
              >
                <Checkbox > Apply for all slot elements</Checkbox>
              </Form.Item>
            </Col>
          </Row>
          <Row gutter={16}>
            <Col span={14}>
              <Form.Item
                name="available_slots"
                label="Avaliable Seats"
                rules={[
                  {
                    required: true,
                    message: 'Please enter bookable seats',
                  },
                ]}
              >
                <InputNumber min={0} />
              </Form.Item>
            </Col>
            <Col span={10}>
              <Form.Item
                name="available_slots_all"
                label=" "
                valuePropName="checked"
                rules={[
                  {
                    required: false,
                  },
                ]}
              >
                <Checkbox > Apply for all slot elements</Checkbox>
              </Form.Item>
            </Col>
          </Row>
          <Row gutter={16}>
            <Col span={14}>
              <Form.Item
                name="show"
                label="Show"
                valuePropName="checked"
                rules={[
                  {
                    required: false,
                  },
                ]}
              >
                <Checkbox> Show the element on the slot template </Checkbox>
              </Form.Item>
            </Col>
            <Col span={10}>
              <Form.Item
                name="show_all"
                label=" "
                valuePropName="checked"
                rules={[
                  {
                    required: false,
                  },
                ]}
              >
                <Checkbox > Apply for all slot elements</Checkbox>
              </Form.Item>
            </Col>
          </Row>
        </Form>
      </Drawer>
    </div>
  );
};
export default SlotElementEditorDrawer;