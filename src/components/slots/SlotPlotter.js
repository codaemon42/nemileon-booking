import { useState, useEffect } from 'react';
import { Col, FloatButton, Row, Badge, message } from "antd";
import { Slot } from './types/Slot.type';
import { SlotCol } from './types/SlotCol.type';
import { SlotRow } from './types/SlotRow.type';
import { EllipsisOutlined, MinusOutlined, PlusOutlined } from '@ant-design/icons';


const SlotPlotter = ({style, defaultSlot = new Slot()}) => {

    // const [gutter, setGutter] = useState(8);
    // const [vgutter, setVgutter] = useState(8);
    // const [cols, setCols] = useState([]);
    // const [rows, setRows] = useState([]);

    const [slot, setSlot] = useState(defaultSlot)


    useEffect(() => {
        if(slot.rows.length <= 0){
            initializer();
        }
    }, [slot])

    const initializer = () => {

        const newSlot = new Slot({
            gutter: 12,
            vGutter: 12,
            product_id: '1',
            allowedBookingPerPerson: 4,
            total: 0,
            rows: SlotRow.List([
                {
                    header: '9 - 10 AM',
                    description: 'some description',
                    showToolTip: false,
                    cols: SlotCol.List([
                        {
                            product_id: '1',
                            content: 'Content',
                            show: true,
                            available_slots: 2,
                            checked: false,
                            booked: 0,
                            expires_in: null
                        },
                        {
                            product_id: '1',
                            content: 'Content',
                            show: true,
                            available_slots: 3,
                            checked: false,
                            booked: 0,
                            expires_in: null
                        },
                        {
                            product_id: '1',
                            content: 'Content',
                            show: true,
                            available_slots: 3,
                            checked: false,
                            booked: 0,
                            expires_in: null
                        }
                    ])
                },
                {
                    header: '10 - 12 AM',
                    description: 'some description',
                    showToolTip: false,
                    cols: SlotCol.List([
                        {
                            product_id: '1',
                            content: 'Content f',
                            show: false,
                            available_slots: 5,
                            checked: false,
                            booked: 0,
                            expires_in: null
                        },
                        {
                            product_id: '1',
                            content: 'Content',
                            show: true,
                            available_slots: 0,
                            checked: false,
                            booked: 0,
                            expires_in: null
                        },
                        {
                            product_id: '1',
                            content: 'Content',
                            show: true,
                            available_slots: 3,
                            checked: false,
                            booked: 0,
                            expires_in: null
                        }
                    ])
                }
            ])
        });

        setSlot(newSlot);
    }


    const setSlotColClass = (addtionalClass = '', colData = new SlotCol()) => {
        let className = addtionalClass;
        if(!colData.show) className += ' hidden-slot ';
        if(!colData.available_slots) className += ' not-available-slot ';
        return className;
    }

    const handleBooking = (rowInd, colInd, value) => {
        const newSlot = {...slot};
        if(value > 0){ // increased
            if(newSlot.rows[rowInd].cols[colInd].booked < newSlot.rows[rowInd].cols[colInd].available_slots && newSlot.total < newSlot.allowedBookingPerPerson){
                newSlot.rows[rowInd].cols[colInd].booked += 1;
                newSlot.rows[rowInd].cols[colInd].expires_in = Date.now();
                newSlot.total += 1;
            }else if(newSlot.total === newSlot.allowedBookingPerPerson){
                message.warning(`You have reached maximum booking`);
            } else {
                message.warning(`No more seats available for booking`);
            }
        } else { // decreased
            newSlot.rows[rowInd].cols[colInd].booked -= 1;
            if(newSlot.rows[rowInd].cols[colInd].booked < 0){
                newSlot.rows[rowInd].cols[colInd].booked = 0;
                newSlot.rows[rowInd].cols[colInd].expires_in = null;
                newSlot.total -= 1;
            }
        }
        setSlot(newSlot);
    }


    return (
        <>
            <Row gutter={[slot.gutter, slot.vGutter]} style={style}>
                {slot.rows.map((rowData, rowInd) => (
                    <>
                        <Col key={`${rowInd}`} span={24 / (rowData.cols.length+1)} >
                            <div className={'playground_header'} >{rowData.header}</div>
                        </Col>
                        {
                        rowData.cols.map((colData, colInd) =>(
                            <Col key={`${rowInd}__${colInd}`} span={24 / (rowData.cols.length+1)} >
                                <Badge className='onsbks_counter' showZero={false} count={colData.booked}>
                                    <div className={setSlotColClass('playground_div', colData)} >
                                        <div >{colData.content}</div>
                                        {
                                            colData.show && colData.available_slots ?
                                            <FloatButton.Group
                                                key={`${rowInd}__${colInd}`}
                                                trigger="click"
                                                type="primary"
                                                style={{
                                                    right: 4,
                                                    bottom: 0,
                                                    position: 'absolute',
                                                    // marginBottom: '0'
                                                }}
                                                shape='square'
                                                icon={<EllipsisOutlined />}
                                                className='slot-booking-menu'
                                                >
                                                <FloatButton 
                                                    onClick={()=>handleBooking(rowInd, colInd, -1)} 
                                                    icon={<MinusOutlined />}
                                                    // style={{marginBottom: '0'}}
                                                />
                                                <FloatButton 
                                                    onClick={()=>handleBooking(rowInd, colInd, 1)} 
                                                    icon={<PlusOutlined />} 
                                                    style={{marginBottom: '-16px'}}
                                                />
                                            </FloatButton.Group>
                                            : <></>
                                        }
                                    </div>
                                </Badge>
                            </Col>
                        ))
                        }
                    </>
                ))}
            </Row>
        </>
    )
}

export default SlotPlotter