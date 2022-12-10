import './bootstrap';

import React from 'react'
import ReactDom from 'react-dom/client'
import App from './components/App'
import { BrowserRouter } from "react-router-dom";

ReactDom.createRoot(document.getElementById('app')).render(
    <BrowserRouter>
        <App />
    </BrowserRouter>
)
