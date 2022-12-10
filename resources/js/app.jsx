import './bootstrap';

import React from 'react'
import ReactDom from 'react-dom/client'
import App from './components/App'

ReactDom.createRoot(document.getElementById('app')).render(
    <App />
)
