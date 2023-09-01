import { useState, useEffect } from 'react'
import { BookingApi } from '../../http/BookingApi';
import { Booking } from './Booking.type'

const useBookings = (userId) => {

    const [bookings, setBookings] = useState(Booking.List());
    const [selectedBooking, setSelectedBooking] = useState(new Booking());
    const [openModal, setOpenModal] = useState(false);
    const [loading, setLoading] = useState(false);
    const [page, setPage] = useState(1);
    const [pageSize, setPageSize] = useState(10);
    const [totalPages, setTotalPages] = useState(1);
    // on Action Handler

    useEffect(() => {
      const abortController = new AbortController();
      getBookings(userId);
    
      return () => {
        abortController.abort();
      }
    }, [userId, page])

    useEffect(() => {
      const abortController = new AbortController();
      getBookingCount();
    
      return () => {
        abortController.abort();
      }
    }, [totalPages])

    const getBookings = async (userId) => {
        setLoading(true);
        const bookingRes = await BookingApi.getBookingsByUserId(userId, page, pageSize);
        setLoading(false);

        if(bookingRes.success) setBookings(bookingRes.result);
        console.log({bookingList: bookingRes.result})

        if(bookingRes.success && bookingRes.result.length > 0 && !selectedBooking.id) setSelectedBooking(bookingRes.result[0])
    }

    const getBookingCount = async () => {
      
      setLoading(true);
      const bookingCountRes = await BookingApi.countBookingsByUserId();
      setLoading(false);
    
      const count = bookingCountRes.result;

      const totalPages = Math.ceil(count / pageSize);
      if(bookingCountRes.success) setTotalPages(totalPages);
  }

    const onPageChange = (page, pageSize) => {
      
      console.log({page, pageSize})
      setPage(page);
    }

    const handleViewBooking = (record, index) => {
      console.time("HANDLE_BOOKING")
      console.log({record, index})
      setOpenModal(true);
      setSelectedBooking(new Booking(record));
      console.timeEnd("HANDLE_BOOKING")
    }

    const handlePayment = (record, index) => {
      console.log({record, index})
      setSelectedBooking(new Booking(record));
    }
    
    const handleCancelBooking = (record, index) => {
      console.info('Cancel Booking for ', {record})
      setBookings(prevBooking => prevBooking.filter(b => b.id !== record.id))
    }

    const handleCloseModal = () => {
      setOpenModal(false);
    }

    return {
        bookings,
        selectedBooking,
        loading,
        openModal,
        page,
        pageSize,
        totalPages,
        onPageChange,
        handleViewBooking,
        handlePayment,
        handleCancelBooking,
        handleCloseModal
    }
}

export default useBookings