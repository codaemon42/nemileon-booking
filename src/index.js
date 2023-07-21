import { render } from '@wordpress/element';
import App from "./App";
import Admin from "./Admin";

/**
 * Import the stylesheet for the plugin.
 */
import './style/main.scss';

// Render the App component into the DOM
render(<App />, document.getElementById('ONSBKS_BOOKING_SECTION'));

// render(<Admin />, document.getElementById('ONSBKS_BOOKING_ADMIN'));