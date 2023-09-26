import { useState, useEffect } from 'react';
import { Col, Row, Spin } from "antd";
import { Slot } from '../slots/types/Slot.type';
import { SlotCol } from '../slots/types/SlotCol.type';
import { BookingApi } from '../../http/BookingApi';

const TicketSlotPlotterPreview = ({style, slot=new Slot(), loading=false}) => {
 

    const setSlotColClass = (addtionalClass = '', colData = new SlotCol()) => {
        let className = addtionalClass;
        if(!colData.show) className += ' hidden-slot ';
        if(!colData.available_slots || !colData.book) className += ' not-available-slot ';
        return className;
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

export default TicketSlotPlotterPreview