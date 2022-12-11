import React from 'react'
import { Routes, Route } from 'react-router-dom'

import CrawlerIndex from '../components/crawlers/Index'
import { CrawlerShowRouter } from '../components/crawlers/Show'
import NotFound from '../components/NotFound'

const Router = () => {
    return (
        <div>
            <Routes>
                <Route path="/" element={ <CrawlerIndex /> } />
                <Route path="/crawler/:id" element={ <CrawlerShowRouter /> } />
                <Route path="/*" element={ <NotFound /> } />
            </Routes>
        </div>
    )
}

export default Router
