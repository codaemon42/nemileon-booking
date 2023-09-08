import React, { useState, useEffect } from 'react';
import jwt_decode from "jwt-decode";
import { Context } from './Context';
import getBrowserFingerprint from 'get-browser-fingerprint';
import { SettingsApi } from '../http/SettingsApi';


const AppContext = ({children}) => {
    const [user, setUser] = useState(null);
    const [settings, setSettings] = useState(null);
    const orderStatuses = Object.keys(reactObj.order_status).map(status => ({label: reactObj.order_status[status], value: reactObj.order_status[status]}), );

    const fingerprint = getBrowserFingerprint({enableWebgl: true, hardwareOnly: true});
    localStorage.setItem('fingerprint', fingerprint);
    console.log({fingerprint});

    useEffect(()=> {
        const abortCtrl = new AbortController();
        const userDecoded = jwt_decode(reactObj.jwt);
        console.log({userDecoded})
        setUser(userDecoded);
        getSettings({signal: abortCtrl.signal});

        return () => {
            abortCtrl.abort();
        }
    }, [])

    const getSettings = async ({signal}) => {
        if(!settings) {
          const settingsRes = await SettingsApi.getSettings({signal});
          if(settingsRes.success) {
            setSettings(settingsRes.result);
          }
        }
    }

    return (
      <Context.Provider value={{context: reactObj, user, setUser, settings, setSettings, orderStatuses}}>
          {children}
      </Context.Provider>
    )
}

export default AppContext