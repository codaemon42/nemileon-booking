import getBrowserFingerprint from 'get-browser-fingerprint';
import React from 'react';
import Admin from './Admin';
import Frontend from './Frontend';
import SlotTemplate from './SlotTemplate';

const App = () => {
    console.log({sbks_react_nonce: reactObj.nonce});
    console.log(reactObj.is_admin);
    const fingerprint = getBrowserFingerprint({enableWebgl: true, hardwareOnly: true});
    console.log({fingerprint});
    
    return (
        reactObj.is_admin 
        ? reactObj.action === 'new' ? <Admin style={{paddingTop: 35}} /> 
            : reactObj.action === 'templates' ? <SlotTemplate style={{marginTop: '35px', marginRight: 5}} />
            : <></>
        : <Frontend />
     );
}

export default App; 