import * as dayjs from 'dayjs';
import { useContext, useState, useEffect } from 'react'
import { Context } from '../../contexts/Context'
import { SettingsApi } from '../../http/SettingsApi';
import { Settings } from './Settings.type';
import duration from 'dayjs/plugin/duration'
dayjs.extend(duration);

let timeInterval;

const useSettings = () => {
    const { context, user, settings, setSettings, orderStatuses } = useContext(Context);

    const [loading, setLoading] = useState(false);
    const [selectedInputField, setSelectedInputField] = useState();

    const [enableAutoCancel, setEnableAutoCancel] = useState(false);
    const [holdingTime, setHoldingTime] = useState(null);

    const [selectedStatuses, setSelectedStatuses] = useState([]);
    const [payNowButtonText, setPayNowButtonText] = useState(null);

    useEffect(() => {
        if(settings){
            const formattedSettings = new Settings(settings);
            console.log({bookingOrderPaidStatuses: formattedSettings.bookingOrderPaidStatuses.split('|')})
            setEnableAutoCancel(formattedSettings.enableAutoCancel);
            setHoldingTime(dayjs.duration({seconds: formattedSettings.autoCancelPeriod}).asMilliseconds());
            setSelectedStatuses([...formattedSettings.bookingOrderPaidStatuses.split('|')]);
            setPayNowButtonText(formattedSettings?.payNowButtonText)
        }
    }, [settings])

    const isLoading = (key) => selectedInputField == key && loading;

    const handleSelectedInput = (value) => {
        setSelectedInputField(value);
    }

    const handleAutoCancel = (check) => {
        setEnableAutoCancel(check);
        updateSettings({enableAutoCancel: check});
    }

    const onChangeTime = (value) => {
        const day = dayjs(value);
        setHoldingTime(dayjs.duration({seconds: day.second(), minutes: day.minute(), hours: day.hour()}).asMilliseconds());
        updateSettings({autoCancelPeriod: dayjs.duration({seconds: day.second(), minutes: day.minute(), hours: day.hour()}).asSeconds()});
    }

    const onChangeOrderStatus = (statusValue) => {
        console.log({statusValue})
        setSelectedStatuses(statusValue);
        updateSettings({bookingOrderPaidStatuses: statusValue.join('|')});
    }

    const onChangeInput = (value)=>{
        if(payNowButtonText !== value) {
            setPayNowButtonText(value);
            debouceUpdate({payNowButtonText: value});
        }
        
    }
    const debouceUpdate = (value)=> {
        if(timeInterval) clearTimeout(timeInterval);
        timeInterval = setTimeout(()=>{
            console.log(value)
            updateSettings(value);
        }, 1000)
    }

    // const getSettings = async ({signal}) => {
    //     if(!settings) {
    //       const settingsRes = await SettingsApi.getSettings({signal});
    //       if(settingsRes.success) {
    //         setSettings(settingsRes.result);
    //       }
    //     }
    // }

    const updateSettings = async (settings) => {
        setLoading(true);

        try{
            const settingsRes = await SettingsApi.saveSettings(settings);
            if(settingsRes.success) setSettings(settingsRes.result);
        } catch(updateError){
            console.log({updateError})
        } finally{
            setLoading(false);
        }
    }

    return {
        loading, isLoading,
        selectedInputField, handleSelectedInput,
        selectedStatuses, orderStatuses, onChangeOrderStatus,
        holdingTime, onChangeTime,
        enableAutoCancel, handleAutoCancel,
        payNowButtonText, onChangeInput,
    }
}

export default useSettings