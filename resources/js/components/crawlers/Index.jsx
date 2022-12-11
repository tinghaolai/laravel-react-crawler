import React from 'react'

import { Input, Button, Pagination, DateRangePicker } from 'element-react'
import { CrawlerShow } from './Show'
import moment from 'moment'

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
                createdAt: [],
                page: 1,
                perPage: 3,
                total: 0,
            },
            crawlerResults: [],
        };

        this._crawlerResult = React.createRef();
    }

    componentDidMount = () => {
        this.searchHistory()
    }

    searchHistory = (page = 1) => {
        this.setState({
            search: {
                ...this.state.search,
                page: page,
            }
        })

        let search = JSON.parse(JSON.stringify(this.state.search));
        search.createdAt = search.createdAt.map(date => {
            return moment(date).format('YYYY-MM-DD HH:mm:ss')
        })

        axios.get('/api/crawler', {
            params: search
        }).then(response => {
            this.setState({
                search: {
                    ...this.state.search,
                    total: response.data.crawlerResults.total,
                },
                crawlerResults: response.data.crawlerResults.data.map(result => {
                    result.displayClass = ''
                    result.displayDetailLinkClass = ''
                    result.displayBodyClass = 'noShow'
                    result.screenShotUrl = result.screenShotPath
                    return <CrawlerShow key={ result.id } assignResult={ result }></CrawlerShow>
                })
            })

            window.scrollTo(0, 0);
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

    handleConditionTitleInputChange = (value) => {
        this.setState({
            search: {
                ...this.state.search,
                title: value,
            }
        })
    }

    handleConditionDescriptionInputChange = (value) => {
        this.setState({
            search: {
                ...this.state.search,
                description: value,
            }
        })
    }

    handleConditionCreatedDateRangeInputChange = (value) => {
        this.setState({
            search: {
                ...this.state.search,
                createdAt: value,
            }
        })
    }

    render () {
        return (
            <div>
                <Input placeholder="Enter url to crawl" onChange={ this.handleUrlInputChange } defaultValue={ this.state.searchUrl } />
                <Button onClick={ this.crawl } disabled={ this.state.crawlerButtonDisabled }>Crawl</Button>
                <CrawlerShow ref={ this._crawlerResult } />
                <hr/>
                <div>History results</div>
                <div>Search title</div>
                <Input placeholder="Enter title to search"
                       onChange={ this.handleConditionTitleInputChange }
                       defaultValue={ this.state.search.title } />

                <div>Search description</div>
                <Input placeholder="Enter description to search"
                       onChange={ this.handleConditionDescriptionInputChange }
                       defaultValue={ this.state.search.description } />

                <div>Create date range</div>
                <DateRangePicker
                    isShowTime={true}
                    type="Date"
                    format="yyyy-MM-dd HH:mm:ss"
                    value={ this.state.search.createdAt }
                    placeholder="select date range"
                    onChange={ this.handleConditionCreatedDateRangeInputChange }
                />

                <div></div>
                <Button onClick={ this.searchHistory }>Search</Button>
                { this.state.crawlerResults }

                <Pagination layout="prev, pager, next"
                            total={ this.state.search.total }
                            currentPage={ this.state.search.page }
                            pageSize={ this.state.search.perPage }
                            onCurrentChange={ this.searchHistory }
                />
            </div>
        )
    }
}

export default CrawlerIndex
