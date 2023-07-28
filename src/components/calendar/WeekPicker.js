import React from 'react'

import { Checkbox, Col, Row } from 'antd';

const WeekPicker = ({onSelect = (checkedElements)=>{}}) => {

    const onChange = (checkedValues) => {
        console.log('checked = ', checkedValues);
        onSelect(checkedValues)
    }

    const colStyle = {
        display: 'flex', 
        flexDirection: 'column',
        gap: 5
    };

    return (
        <>
            <Checkbox.Group
                style={{
                    width: '100%',
                }}
                className='onsbks-round-box'
                onChange={onChange}
                >

                <Row>
                    <Col span={8} style={colStyle}>
                        {/* <Checkbox className='onsbks-checkbox' value="SUN">Sunday</Checkbox> */}
                        <Checkbox className='onsbks-checkbox' value="MON">Monday</Checkbox>
                        <Checkbox className='onsbks-checkbox' value="THU">Thursday</Checkbox>
                    </Col>
                    <Col span={8} style={colStyle}>
                        <Checkbox className='onsbks-checkbox' value="TUE">Tuseday</Checkbox>
                        <Checkbox className='onsbks-checkbox' value="FRI">Friday</Checkbox>
                    </Col>
                    <Col span={8} style={colStyle}>
                        <Checkbox className='onsbks-checkbox' value="WED">Wednesday</Checkbox>
                        <Checkbox className='onsbks-checkbox' value="SAT">Saturday</Checkbox>
                    </Col>
                    <Col span={24} style={{marginTop: 5 ,...colStyle}}>
                        <Checkbox className='onsbks-checkbox' value="SUN">Sunday</Checkbox>
                    </Col>
                </Row>
            </Checkbox.Group>
        </>
    )
};

export default WeekPicker
