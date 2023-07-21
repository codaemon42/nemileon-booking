export class HttpResponseData {
    #default = {
        success: true,
        result: [],
        message: false,
    }

    constructor(data=null) {
        if(!data) data = this.#default;
        this.success = data?.success;
        this.result = data?.result;
        this.message = data?.message;
    }


    static List(initialValue = []) {
            const arr = [new this()];
            arr.shift();
            if(initialValue && initialValue.length > 0) initialValue.map(v => arr.push(v));
            return arr;
    }
}