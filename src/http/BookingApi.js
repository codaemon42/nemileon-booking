import { ajax } from "./ajax";
import { HttpResponseData } from "./HttpResponseData";
import { http, prepareUrl } from "./http";
import { Booking } from "../components/bookings/Booking.type";

class BookingApi {

    static async createPayment({bookingId}){
        let body = {
            action: 'onsbks_add_to_cart',
            _wpnonce: reactObj.nonce,
            bookingId: bookingId || 0
        }
        const bookingRes = await ajax.post(reactObj.ajax_url, {...body});
        return new HttpResponseData(bookingRes.data);
    }

    static async createBooking( data  ) {
        const bookingsResponse = await http.post(prepareUrl(`/bookings`), data);
        return new BookingsResponseData(bookingsResponse.data);
    }

    static async getBookingsByUserId( userId, paged, per_page ) {
        const bookingsResponse = await http.get(prepareUrl(`/bookings/users/${userId}?paged=${paged}&per_page=${per_page}`));
        return new BookingsResponseData(bookingsResponse.data);
    }

    static async countBookingsByUserId() {
        const bookingsCountResponse = await http.get(prepareUrl(`/bookings/users/count`));
        return new HttpResponseData(bookingsCountResponse.data);
    }
}


class BookingsResponseData extends HttpResponseData{
    constructor(data=null){
        super(data);
        this.result = Booking.List(this.result);
    }
}

export { BookingApi }
