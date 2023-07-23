import { SlotTemplateType } from "../components/slots/types/SlotTemplateType.type";
import { http, prepareUrl } from "./http";
import { HttpResponseData } from "./HttpResponseData";

class SlotTemplateApi {


    static async getSlotTemplates(){
        const slots = await http.get(prepareUrl('/templates'));
        return new SlotTemplateResponseData(slots.data);
    }


    static async createSlotTemplate($data = new SlotTemplateType()){
        const slotTemplateCreateRes = await http.post(prepareUrl('/templates'), $data);
        const slotTemplateId = slotTemplateCreateRes.data?.result || 0;
        $data.id = slotTemplateId;
        $data.key = slotTemplateId;
        slotTemplateCreateRes.data.result = $data;
        return new SingleSlotTemplateResponseData(slotTemplateCreateRes.data);
    }

    static async deleteSlotTemplates(id){
        const slots = await http.delete(prepareUrl(`/templates?id=${id}`));
        return new HttpResponseData(slots.data);
    }
}


class SlotTemplateResponseData extends HttpResponseData{
    constructor(data=null){
        super(data);
        this.result = SlotTemplateType.List(this.result);
    }
}

class SingleSlotTemplateResponseData extends HttpResponseData{
    constructor(data=null){
        super(data);
        this.result = new SlotTemplateType(this.result);
    }
}

export { SlotTemplateApi }