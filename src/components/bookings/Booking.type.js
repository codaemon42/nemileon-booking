import { Slot } from "../slots/types/Slot.type";
import BookingStatus from "./BookingStatus";

export class Booking {
    #default = {
        id: 0,
        user_id: '',
        finger_print: '',
        name: '',
        booking_date: '',
        seats: '',
        product_id: 0,
        headers: '',
        top_header: '',
        total_price: 0,
        status: BookingStatus.PENDING_PAYMENT,
        expired: false,
        expires_in: '',
        template: new Slot()
    }

    constructor(data=null) {
        if(!data) data = this.#default;
        this.id = data?.id;
        this.user_id = data?.user_id;
        this.finger_print = data?.finger_print;
        this.name = data?.name;
        this.booking_date = data?.booking_date;
        this.seats = data?.seats;
        this.product_id = data?.product_id;
        this.headers = data?.headers;
        this.top_header = data?.top_header;
        this.total_price = data?.total_price;
        this.status = data?.status;
        this.expired = data?.expired;
        this.expires_in = data?.expires_in;
        this.template = new Slot(data?.template);

    }


    static List(initialValue = []) {
            const arr = [new this()];
            arr.shift();
            if(initialValue && initialValue.length > 0) initialValue.map(v => arr.push(new this(v)));
            return arr;
    }
}