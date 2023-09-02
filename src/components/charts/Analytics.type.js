export class Analytics {
    #default = {
        xAxis: '0',
        yAxis: 0,
        type: '',
        mockType: 'SEATS'
    }

    constructor(data=null) {
        if(!data) data = this.#default;
        this.xAxis = data?.xAxis;
        this.yAxis = parseInt(data?.yAxis);
        this.type = data?.type;
        this.mockType = 'SEATS';
    }


    static List(initialValue = []) {
            const arr = [new this()];
            arr.shift();
            if(initialValue && initialValue.length > 0) initialValue.map(v => arr.push(new this(v)));
            return arr;
    }
}