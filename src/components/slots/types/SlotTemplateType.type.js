import { Slot } from "./Slot.type";

export class SlotTemplateType{
    #default = {
        id: "",
        key: 0,
        name: "",
        template: new Slot()
    }

    constructor(data=null) {
        if(!data) data = this.#default;
        this.id = data?.id;
        this.key = data?.id;
        const date = new Date()
        this.name = data?.name || `Name-${date.toISOString().split('T')[0]}`
        this.template = typeof data?.template == 'string' ?  new Slot(JSON.parse(data?.template)) : data?.template;
    }


    static List(initialValue = []) {
            const arr = [new this()];
            arr.shift();
            if(initialValue && initialValue.length > 0) initialValue.map(v => arr.push(new this(v)));
            return arr;
    }
}