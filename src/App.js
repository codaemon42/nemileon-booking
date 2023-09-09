import React, { useContext } from 'react';
import Admin from './pages/Admin';
import Bookings from './pages/Bookings';
import { Context } from './contexts/Context';
import Dashboard from './pages/Dashboard';
import Frontend from './pages/Frontend';
import OrderEdit from './pages/OrderEdit';
import SlotTemplate from './pages/SlotTemplate';
import Settings from './pages/Settings';

const App = () => {

    const { context, user } = useContext(Context);

    console.log({context, user})
    
    return (
        context.is_admin 
        ? context.page === 'nml-sports-booking-slot' ? <Dashboard style={{paddingTop: 35}} /> 
            : context.page === 'nml-product-templates' ? <Admin style={{paddingTop: 20}} />
            : context.page === 'nml-slot-templates' ? <SlotTemplate style={{marginTop: '35px', marginRight: 5}} />
            : context.page === 'nml-bookings' ? <Bookings style={{paddingTop: 35, marginRight: 5}} />
            : context.page === 'nml-settings' ? <Settings style={{ marginRight: 5}} />
            : context.action === "edit" ? <OrderEdit />
            : <></>
        : <Frontend />
        
     );
}

export default App; 