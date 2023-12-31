import { Divider, Modal, Table} from 'antd'
import React from 'react'
import BookingButtonGroup from './BookingButtonGroup';
import useBookings from './useBookings';
import SlotPlotterPreview from '../slots/SlotPlotterPreview';

const BookingsTable = () => {

    const { bookings, selectedBooking, page, pageSize, totalPages, openModal, loading: bookingLoading, handleViewBooking, handlePayment, handleCancelBooking, handleCloseModal, onPageChange } = useBookings();
  
  
    const columns = [
        {
            title: "ID",
            dataIndex: "id",
            key: "id"
        },
        {
            title: "Name",
            dataIndex: "name",
            key: "name"
        },
        {
            title: "Booking Date",
            dataIndex: "booking_date",
            key: "booking_date",
        },
        {
            title: "Seats",
            dataIndex: "seats",
            key: "seats"
        },
        {
            title: "Status",
            dataIndex: "status",
            key: "status"
        },
        {
            title: "Action",
            key: "action",
            render: (_, record, index) => (
                <BookingButtonGroup 
                    record={record}
                    index={index}
                    onView={handleViewBooking} 
                    onBook={handlePayment}
                    onCancel={handleCancelBooking}
                />
            ),
        },
    ];

  
    return (
        <>
            <Table
                data-testId="bookings-user-table"
                loading={bookingLoading}
                columns={columns}
                dataSource={bookings}
                pagination={{ 
                    current: page,
                    pageSize: pageSize, 
                    total: totalPages,
                    onChange: onPageChange
                }}
            />
            <Modal 
              title={`Preview Booking : ${selectedBooking.id}`}
              open={openModal} 
              onOk={handleCloseModal} 
              onCancel={handleCloseModal} 
              cancelText='Close'
              width={window.innerWidth*0.6}
              >
              <Divider orientation="left" plain></Divider>
              <SlotPlotterPreview key={selectedBooking.id} defaultSlot={selectedBooking.template} disableIncrement={true} />
            </Modal>
        </>
    );
};
export default BookingsTable