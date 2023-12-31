import { message } from 'antd';
import { useContext, useEffect, useState } from 'react';
import { Context } from '../../contexts/Context';
import { TicketApi } from '../../http/TicketApi';
import { TicketType } from '../tickets/Ticket.type';

const useTicket = ({verify = false}) => {

    const { context }  = useContext(Context);
    const {ticket: bookingId, currency_symbol: currency, site_title: siteTitle, logoUrl } = context;

    const [ticket, setTicket] = useState(new TicketType());
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        getTicketByBookingId();
    }, [bookingId])
    

    const getTicketByBookingId = async () => {
        setLoading(true);
        let sTicket;
        if(!verify){
            sTicket = await TicketApi.getTicketById(bookingId);
            if(sTicket && !sTicket.success && sTicket.message === "Sorry, you are not allowed to do that."){
                message.error("Please log in or use the same browser you booked the ticket from")
            }
        } else {
            sTicket = await TicketApi.verifyTicketById(bookingId);
        }
        setLoading(false);
        if(sTicket && sTicket.success) {
            setTicket(sTicket.result);
        }
    }

    return {
        ticket,
        currency,
        siteTitle,
        loading,
        logoUrl
    }
}

export default useTicket