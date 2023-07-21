import { CheckOutlined, EditOutlined } from '@ant-design/icons'
import { Button, Col, Input, Row } from 'antd'
import {useState} from 'react'

const EditInput = ({defaultValue, onChangeText}) => {

  const [editable, setEditable] =  useState(false);
  const [text, setText] = useState(defaultValue);


    const handleInput = (event) => {
        console.log({text})
        setText(event.target.value);
    }

    const handleSubmit = () => {
        console.log({text})
        setEditable(false);
        onChangeText(text)
    }

  return (
    <>
        {!editable ?
            <Row>
                <Col span={20}>
                    <div>{text}</div>
                </Col>
                <Col span={4}>
                    <Button onClick={()=> setEditable(true)} icon={<EditOutlined />} />
                </Col>
            </Row>
            :
            <Row>
                <Col span={20}>
                    <div 
                        style={{
                            width: '90%',
                            margin: 'auto'
                        }}
                    >
                        <Input defaultValue={text} onChange={handleInput} />
                    </div>
                </Col>
                <Col span={4}>
                    <Button onClick={handleSubmit} icon={<CheckOutlined />} />
                </Col>
            </Row>
        }
    </>
  )
}

export default EditInput