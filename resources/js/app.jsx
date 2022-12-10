import './bootstrap';

import React from 'react'
import ReactDom from 'react-dom/client'
import App from './components/App'
import { BrowserRouter } from "react-router-dom";

import 'element-theme-default'

ReactDom.createRoot(document.getElementById('app')).render(
    <BrowserRouter>
        <App />
    </BrowserRouter>
)
