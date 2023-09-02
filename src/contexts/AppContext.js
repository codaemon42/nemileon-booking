import React, { useState, useEffect } from 'react';
import jwt_decode from "jwt-decode";
import { Context } from './Context';


const AppContext = ({children}) => {
    const [user, setUser] = useState(null);
    const fingerprint = getBrowserFingerprint({enableWebgl: true, hardwareOnly: true});
    localStorage.setItem('fingerprint', fingerprint);
    console.log({fingerprint});

    useEffect(()=> {
        const userDecoded = jwt_decode(reactObj.jwt);
        console.log({userDecoded})
        setUser(userDecoded);
    }, [])

  return (
    <Context.Provider value={{context: reactObj, user, setUser}}>
        {children}
    </Context.Provider>
  )
}

export default AppContext