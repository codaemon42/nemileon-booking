import { Slot } from "../slots/types/Slot.type";

export class TicketType {
    #default = {
        id: 0,
        name: '', // issuer name
        email: '',
        phone: '',
        booking_date: '',
        seats: 0,
        total_price: 0,
        product_name: '', // name of booking
        template: new Slot()
    }

    constructor(data=null) {
        if(!data) data = this.#default;
        this.id = data?.id;
        this.name = data?.name;
        this.email = data?.email;
        this.phone = data?.phone;
        this.booking_date = data?.booking_date;
        this.seats = data?.seats;
        this.total_price = data?.total_price;
        this.product_name = data?.product_name;
        this.template = new Slot(data?.template);
    }


    static List(initialValue = []) {
            const arr = [new this()];
            arr.shift();
            if(initialValue && initialValue.length > 0) initialValue.map(v => arr.push(new this(v)));
            return arr;
    }
}