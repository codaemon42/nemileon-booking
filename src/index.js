import { render } from '@wordpress/element';
import App from "./App";

/**
 * Import the stylesheet for the plugin.
 */
import './style/main.scss';
import AppContext from './contexts/AppContext';

// Render the App component into the DOM
render(
    <AppContext>
        <App />
    </AppContext>,
    document.getElementById('ONSBKS_BOOKING_SECTION')
);

// render(<Admin />, document.getElementById('ONSBKS_BOOKING_ADMIN'));