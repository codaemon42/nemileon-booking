import { SlotRow } from "./SlotRow.type";

export class Slot {
    #default = {
        gutter: 8,
        vGutter: 8,
        rows: SlotRow.List(),
        allowedBookingPerPerson: 100,
        total: 0
    }

    constructor(data=null) {
        if(!data) data = this.#default;
        this.gutter = data?.gutter;
        this.vGutter = data?.vGutter;
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
