import { SlotTemplateType } from "../components/slots/types/SlotTemplateType.type";
import { http, prepareUrl } from "./http";
import { HttpResponseData } from "./HttpResponseData";

class SlotTemplateApi {


    static async getSlotTemplates(){
        const slots = await http.get(prepareUrl('/templates'));
        return new SlotTemplateResponseData(slots.data);
    }
}


class SlotTemplateResponseData extends HttpResponseData{
    constructor(data=null){
        super(data);
        this.result = SlotTemplateType.List(this.result);
    }
}

export { SlotTemplateApi }