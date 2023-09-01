import { ajax } from "./ajax";
import { HttpResponseData } from "./HttpResponseData";
import { http, prepareUrl } from "./http";
import { Booking } from "../components/bookings/Booking.type";

class BookingApi {

    static async createBooking({productId, slots}){
        let body = {
            action: 'onsbks_cart_action',
            _wpnonce: reactObj.nonce,
            product_id: productId || 0,
            slots: slots || "test"
        }
        const bookingRes = await ajax.post(reactObj.ajax_url, {...body});
        return new HttpResponseData(bookingRes.data);
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
