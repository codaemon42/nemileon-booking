import { TicketType } from "../components/tickets/Ticket.type";
import { http, prepareUrl } from "./http";
import { HttpResponseData } from "./HttpResponseData";

class TicketApi {

    static async getTicketById( id ) {
        const ticketResponse = await http.get(prepareUrl(`/tickets/find/${id}`));
        return new TicketResponseData(ticketResponse.data);
    }

    static async verifyTicketById( id ) {
        const ticketResponse = await http.get(prepareUrl(`/tickets/verify/${id}`));
        return new TicketResponseData(ticketResponse.data);
    }

}


class TicketResponseData extends HttpResponseData{
    constructor(data=null){
        super(data);
        this.result = new TicketType(this.result);
    }
}


export { TicketApi };

