import { Col, Divider, Row } from 'antd';
import React from 'react';
import Admin from './Admin';
import BoxCalendar from './components/calendar/BoxCalendar';
import ProductsTable from './components/products/ProductsTable';
import SlotBuilder from './components/slots/SlotBuilder';
import SlotPlotter from './components/slots/SlotPlotter';
import Frontend from './Frontend';
import SlotTemplate from './SlotTemplate';

const App = () => {
    console.log({sbks_react_nonce: reactObj.nonce});
    console.log(reactObj.is_admin);
    
    return (
        reactObj.is_admin 
        ? reactObj.action === 'new' ? <Admin /> 
            : reactObj.action === 'templates' ? <SlotTemplate style={{marginTop: '35px', marginRight: 5}} />
            : <></>
        : <Frontend />
     );
}

export default App; 