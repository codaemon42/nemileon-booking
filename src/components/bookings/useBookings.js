import { message } from "antd";
import { useState, useEffect, useContext } from "react";
import { Context } from "../../contexts/Context";
import { BookingApi } from "../../http/BookingApi";
import { Booking } from "./Booking.type";
import BookingStatus from "./BookingStatus";

const useBookings = () => {
  const { context, user } = useContext(Context)

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
    getBookings(user?.user_id);

    return () => {
      abortController.abort();
    };
  }, [user, page]);

  useEffect(() => {
    const abortController = new AbortController();
    getBookingCount();

    return () => {
      abortController.abort();
    };
  }, [totalPages]);

  const getBookings = async (userId=0) => {
    setLoading(true);
    const bookingRes = await BookingApi.getBookingsByUserId( userId, page, pageSize );
    setLoading(false);

    console.log({bookingRes})
    if (bookingRes.success) setBookings(bookingRes.result);

    // if(!bookingRes.success) message.error(bookingRes.message)
    // console.log({ bookingList: bookingRes.result });

    if ( bookingRes.success && bookingRes.result.length > 0 && !selectedBooking.id ) {
      setSelectedBooking(bookingRes.result[0]);
    }
  };

  const getBookingCount = async () => {
    setLoading(true);
    const bookingCountRes = await BookingApi.countBookingsByUserId();
    setLoading(false);

    const count = bookingCountRes.result;

    const totalPages = Math.ceil(count / pageSize);
    if (bookingCountRes.success) setTotalPages(totalPages);
  };

  const onPageChange = (page, pageSize) => {
    console.log({ page, pageSize });
    setPage(page);
  };

  const handleViewBooking = (record, index) => {
    console.time("HANDLE_BOOKING");
    console.log({ record, index });
    setOpenModal(true);
    setSelectedBooking(new Booking(record));
    console.timeEnd("HANDLE_BOOKING");
  };

  const handlePayment = async (record, index) => {
    console.log({ record, index });
    setSelectedBooking(new Booking(record));
    const paymentRes = await BookingApi.createPayment({bookingId: record.id});
    if(paymentRes) {
      console.log('REDIRECTING TO CHECKOUT');
      window.location.replace(paymentRes.result);
    }
  };

  const handleCancelBooking = (record, index) => {
    console.info("Cancel Booking for ", { record });
    setBookings((prevBooking) => prevBooking.filter((b) => b.id !== record.id));
  };

  const handleCloseModal = () => {
    setOpenModal(false);
  };

  const handleFinishCountDown = (record, index) => {
    const newBookings = [...bookings];
    newBookings[index].expired = true;
    newBookings[index].status = BookingStatus.CANCELLED;
    setBookings(newBookings)
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
    handleCloseModal,
    handleFinishCountDown
  };
};

export default useBookings;
