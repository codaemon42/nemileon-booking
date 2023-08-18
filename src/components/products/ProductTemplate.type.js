import { Slot } from "../slots/types/Slot.type";

export class ProductTemplateType {
    #default = {
        id: 0,
        product_id: 0,
        key: '',
        template: new Slot(),
    }

    constructor(data=null) {
        if(!data) data = this.#default;
        this.id = data?.id;
        this.key = data?.key;
        this.product_id = data?.product_id;
        this.template = new Slot(data?.template);

    }


    static List(initialValue = []) {
            const arr = [new this()];
            arr.shift();
            if(initialValue && initialValue.length > 0) initialValue.map(v => arr.push(new this(v)));
            return arr;
    }
}