import { SlotRow } from "./SlotRow.type";

export class Slot {
    #default = {
        gutter: 12,
        vGutter: 12,
        rows: SlotRow.List(),
        allowedBookingPerPerson: 100,
        total: 0
    }

    constructor(data=null) {
        if(!data) data = this.#default;
        this.gutter = parseInt(data?.gutter);
        this.vGutter = parseInt(data?.vGutter);
        this.rows = SlotRow.List(data?.rows);
        this.allowedBookingPerPerson = data?.allowedBookingPerPerson || 100;
        this.total = data?.total || 0;
    }


    static List(initialValue = []) {
            const arr = [new this()];
            arr.shift();
            if(initialValue && initialValue.length > 0) initialValue.map(v => arr.push(new this(v)));
            return arr;
    }
}
