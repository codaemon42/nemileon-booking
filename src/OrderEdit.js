import { Divider } from 'antd'
import { useState, useEffect } from 'react'
import { Booking } from './components/bookings/Booking.type'
import OrderSlotPlotterPreview from './components/orders/OrderSlotPlotterPreview'
import { Slot } from './components/slots/types/Slot.type'
import { BookingApi } from './http/BookingApi'

const OrderEdit = () => {
    const bookingId = document.getElementById('ONSBKS_BOOKING_SECTION').dataset.bookingId;
    return (
        <>
            <Divider orientation="center" > <h3> Booking Preview  #{bookingId} </h3></Divider>
            <OrderSlotPlotterPreview disableIncrement={true} />
        </>
    )
}

export default OrderEdit