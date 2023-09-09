export class Settings {
    #default = {
        enableAutoCancel: true,
        autoCancelPeriod: 3660,
        payNowButtonText: 'Pay Now',
        bookingOrderPaidStatuses: "Completed"
    }

    constructor(data=null) {
        if(!data) data = this.#default;
        this.enableAutoCancel = data?.enableAutoCancel,
        this.autoCancelPeriod = data?.autoCancelPeriod,
        this.payNowButtonText = data?.payNowButtonText,
        this.bookingOrderPaidStatuses = data?.bookingOrderPaidStatuses
    }


    static List(initialValue = []) {
            const arr = [new this()];
            arr.shift();
            if(initialValue && initialValue.length > 0) initialValue.map(v => arr.push(new this(v)));
            return arr;
    }
}