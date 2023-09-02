import getBrowserFingerprint from 'get-browser-fingerprint';
import React, { useContext } from 'react';
import Admin from './Admin';
import Bookings from './Bookings';
import { Context } from './contexts/Context';
import Dashboard from './Dashboard';
import Frontend from './Frontend';
import SlotTemplate from './SlotTemplate';

const App = () => {

    const { context, user } = useContext(Context);

    console.log({context, user})
    
    return (
        context.is_admin 
        ? context.page === 'nml-sports-booking-slot' ? <Dashboard style={{paddingTop: 35}} /> 
            : context.page === 'nml-product-templates' ? <Admin style={{paddingTop: 35}} />
            : context.page === 'nml-slot-templates' ? <SlotTemplate style={{marginTop: '35px', marginRight: 5}} />
            : context.page === 'nml-bookings' ? <Bookings style={{paddingTop: 35, marginRight: 5}} />
            : <></>
        : <Frontend />
        
     );
}

export default App; 