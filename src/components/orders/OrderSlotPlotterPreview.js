import { useState, useEffect } from 'react';
import { Col, FloatButton, Row, Badge, message, Divider, Spin } from "antd";
import { Slot } from '../slots/types/Slot.type';
import { SlotCol } from '../slots/types/SlotCol.type';
import { SlotRow } from '../slots/types/SlotRow.type';
import { EllipsisOutlined, MinusOutlined, PlusOutlined } from '@ant-design/icons';
import { BookingApi } from '../../http/BookingApi';

const OrderSlotPlotterPreview = ({style, disableIncrement=false}) => {

    const [slot, setSlot] = useState(new Slot())
    const [loading, setLoading] = useState(false);
    const bookingId = document.getElementById('ONSBKS_BOOKING_SECTION').dataset.bookingId;


    useEffect(() => {
        getBooking();
    }, [])


    const getBooking = async () => {
        if(bookingId) {
            setLoading(true);
            const bookingRes = await BookingApi.getBookingById(bookingId);
            setLoading(false);
            if(bookingRes.success){
                setSlot(bookingRes.result.template);
            }
        }
    }

    const setSlotColClass = (addtionalClass = '', colData = new SlotCol()) => {
        let className = addtionalClass;
        if(!colData.show) className += ' hidden-slot ';
        if(!colData.available_slots || !colData.book) className += ' not-available-slot ';
        return className;
    }

    const handleBooking = (rowInd, colInd, value) => {
        const newSlot = {...slot};
        if(value > 0){ // increased
            if(newSlot.rows[rowInd].cols[colInd].available_slots && newSlot.total < newSlot.allowedBookingPerPerson){
                newSlot.rows[rowInd].cols[colInd].book += 1;
                newSlot.rows[rowInd].cols[colInd].checked = true;
                newSlot.rows[rowInd].cols[colInd].available_slots -= 1;
                newSlot.rows[rowInd].cols[colInd].expires_in = Date.now();
                newSlot.total += 1;
            }else if(newSlot.total === newSlot.allowedBookingPerPerson){
                message.warning(`You have reached maximum booking`);
            } else {
                message.warning(`No more seats available for booking`);
            }
        } else { // decreased
            if(newSlot.rows[rowInd].cols[colInd].book > 0 && newSlot.total > 0 && newSlot.total <= newSlot.allowedBookingPerPerson){
                newSlot.rows[rowInd].cols[colInd].book -= 1;
                newSlot.rows[rowInd].cols[colInd].available_slots += 1;
                newSlot.total -= 1;
            }
            if(newSlot.rows[rowInd].cols[colInd].book === 0){
                newSlot.rows[rowInd].cols[colInd].expires_in = null;
                newSlot.rows[rowInd].cols[colInd].checked = false;
            }
        }
        setSlot(newSlot);
    }


    return (
        <>
            <Row >
                <Col span={24}>
                    <div style={{display: 'flex', justifyContent: 'center', alignItems: 'center'}}>
                        <Spin spinning={loading}></Spin>
                    </div>
                </Col>
            </Row>
            <Row gutter={[slot.gutter, slot.vGutter]} style={style}>
                {slot.rows.map((rowData, rowInd) => (
                    <>
                        <Col key={`${rowInd}`} span={24 / (rowData.cols.length+1)} >
                            <div className={'playground_header'} >{rowData.header}</div>
                        </Col>
                        {
                        rowData.cols.map((colData, colInd) =>(
                            <Col key={`${rowInd}__${colInd}`} span={24 / (rowData.cols.length+1)} >
                                {console.log({colData})}
                                    <div className={setSlotColClass('playground_div', colData)} >
                                        <div>
                                            <div>{colData.content}</div>
                                            <div>Booked</div>
                                            <div 
                                                style={{
                                                    fontSize: 16,
                                                    fontWeight: 'bold',
                                                    textShadow: '1px 1px 1px #3333'
                                                }}
                                            >
                                                {colData.book}
                                            </div>
                                        </div>
                                        {
                                            colData.show && (colData.available_slots || colData.checked) && !disableIncrement ?
                                            <FloatButton.Group
                                                key={`${rowInd}__${colInd}`}
                                                trigger="click"
                                                type="primary"
                                                style={{
                                                    right: 0,
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
                            </Col>
                        ))
                        }
                    </>
                ))}
            </Row>
        </>
    )
}

export default OrderSlotPlotterPreview