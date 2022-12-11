import React from 'react'
import { Routes, Route } from 'react-router-dom'

import CrawlerIndex from '../components/crawlers/Index'
import CrawlerShow from '../components/crawlers/Show'
import NotFound from '../components/NotFound'

const Router = () => {
    return (
        <div>
            <Routes>
                <Route path="/" element={ <CrawlerIndex /> } />
                <Route path="/crawler/:id" element={ <CrawlerShow /> } />
                <Route path="/*" element={ <NotFound /> } />
            </Routes>
        </div>
    )
}

export default Router
