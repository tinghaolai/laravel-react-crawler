import React from 'react'

import { Input, Button } from 'element-react'
import { CrawlerShow } from './Show'

class CrawlerIndex extends React.Component
{
    constructor(props) {
        super(props);

        this.state = {
            searchUrl : 'https://github.com/',
            crawlerResultDisplay: false,
            crawlerButtonDisabled: false,
            crawledId: null,
            search: {
                title: '',
                description: '',
                createdAt: '',
                page: 1,
                perPage: 3,
                total: 0,
            },
            crawlerResults: [],
        };

        this._crawlerResult = React.createRef();
        this.searchHistory()
    }

    searchHistory = () => {
        axios.get('/api/crawler', {
            params: this.state.search
        }).then(response => {
            console.log(response.data)
            this.setState({ crawlerResults: response.data.crawlerResults.data.map(result => {
                    result.displayClass = ''
                    result.displayDetailLinkClass = ''
                    result.displayBodyClass = 'noShow'
                    result.screenShotUrl = result.screenShotPath
                    return <CrawlerShow key={ result.id } assignResult={ result }></CrawlerShow>
                })})
        }).catch(error => {
            alert(error.message)
        })
    }

    crawl = () => {
        if (!this.state.searchUrl) {
            alert('url cant be empty')
            return
        }

        this.setState({ crawlerButtonDisabled: true })
        axios.post('/api/crawler', {
            url: this.state.searchUrl
        }).then(response => {
            this.setState({
                crawlerButtonDisabled: false,
                crawledId: response.data.id,
                crawlerResultDisplay: true,
            })

            this._crawlerResult.current.setBodyDisplay(false)
            this._crawlerResult.current.setDetailLinkDisplay(true)
            this._crawlerResult.current.fetch(response.data.id)
        }).catch(error => {
            alert(error.message)
            this.setState({ crawlerButtonDisabled: false })
        })
    }

    handleUrlInputChange = (value) => {
        this.setState({ searchUrl: value })
    }

    render () {
        return (
            <div>
                <Input placeholder="Enter url to crawl" onChange={ this.handleUrlInputChange } defaultValue={ this.state.searchUrl } />
                <Button onClick={ this.crawl } disabled={ this.state.crawlerButtonDisabled }>Crawl</Button>
                <CrawlerShow ref={ this._crawlerResult } />
                <hr/>
                <div>History results</div>
                { this.state.crawlerResults }
            </div>
        )
    }
}

export default CrawlerIndex
