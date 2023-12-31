export class SlotCol {
    #default = {
        product_id: '1',
        content: 'Content',
        show: false,
        available_slots: 0,
        checked: false,
        booked: 0,
        expires_in: null,
        book: 0,
    }

    constructor(data=null) {
        if(!data) data = this.#default;
        this.product_id = data?.product_id;
        this.content = data?.content;
        this.show = data?.show;
        this.available_slots = data?.available_slots;
        this.checked = data?.checked;
        this.booked = data?.booked || 0;
        this.expires_in = data?.expires_in || null;
        this.book = data?.book || 0;
    }


    static List(initialValue = []) {
            const arr = [new this()];
            arr.shift();
            if(initialValue && initialValue.length > 0) initialValue.map(v => arr.push(new this(v)));
            return arr;
    }
}