import { useEffect, useState } from 'react'
import { AnalyticsApi } from '../../http/AnalyticsApi'
import { Analytics } from './Analytics.type'

const useAnalytics = () => {

    const [BookingSeatAnalyticsByDate, setBookingSeatAnalyticsByDate] = useState(Analytics.List())
    const [BookingSeatAnalyticsByDateAndStatus, setBookingSeatAnalyticsByDateAndStatus] = useState(Analytics.List())

    useEffect(() => {
        const abortController = new AbortController();
        
        getBookingSeatAnalyticsByDate({signal: abortController.signal});

        getBookingSeatAnalyticsByDateAndStatus({signal: abortController.signal});
    
        return () => {
            abortController.abort();
        }
    }, [])
    

    const getBookingSeatAnalyticsByDate = async ({signal = null}) => {
        const analyticsRes = await AnalyticsApi.getBookingsAnalyticsByDate({signal});
        console.log({analyticsRes})
        if(analyticsRes.success) setBookingSeatAnalyticsByDate(analyticsRes.result);
    };

    const getBookingSeatAnalyticsByDateAndStatus = async ({signal = null}) => {
        const analyticsRes = await AnalyticsApi.getBookingsAnalyticsByDateAndStatus({signal});
        console.log({analyticsRes})
        if(analyticsRes.success) setBookingSeatAnalyticsByDateAndStatus(analyticsRes.result);
    };

    return {
        BookingSeatAnalyticsByDate,
        BookingSeatAnalyticsByDateAndStatus
    };
}

export default useAnalytics