import { SlotCol } from "./SlotCol.type";

export class SlotRow {
    #default = {
        header: '',
        description: '',
        showToolTip: false,
        cols: SlotCol.List()
    }

    constructor(data=null) {
        if(!data) data = this.#default;
        this.header = data?.header;
        this.description = data?.description;
        this.showToolTip = data?.showToolTip;
        this.cols = SlotCol.List(data?.cols);
    }

    static List(initialValue = []) {
            const arr = [new this()];
            arr.shift();
            if(initialValue && initialValue.length > 0) initialValue.map(v => arr.push(new this(v)));
            return arr;
    }
}