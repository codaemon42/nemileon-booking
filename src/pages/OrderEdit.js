import { Divider } from 'antd'
import OrderSlotPlotterPreview from '../components/orders/OrderSlotPlotterPreview'

const OrderEdit = () => {
    const bookingId = document.getElementById('ONSBKS_BOOKING_SECTION').dataset.bookingId;
    return (
        <>
            <Divider orientation="center" > <h3> Booking Preview  #{bookingId} </h3></Divider>
            <OrderSlotPlotterPreview bookingId={bookingId}/>
        </>
    )
}

export default OrderEdit