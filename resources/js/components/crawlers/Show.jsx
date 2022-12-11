import React from 'react'

import { withRouter } from '../../router/withRouter'

class CrawlerShow extends React.Component
{
    constructor(props) {
        super(props);

        this.state = {
            displayClass: 'noShow',
            id: null,
            title: null,
            url: null,
            screenShotUrl: null,
            description: null,
            body: null,
            createdAt: null,
            displayBodyClass: '',
            displayDetailLinkClass: 'noShow',
        };

        if (this.props.params && this.props.params.id) {
            this.fetch(this.props.params.id)
        }
    }

    setBodyDisplay = (display) => {
        this.setState({ displayBodyClass: display ? '' : 'noShow' })
    }

    setDetailLinkDisplay = (display) => {
        this.setState({ displayDetailLinkClass: display ? '' : 'noShow' })
    }

    fetch = (id) => {
        axios.get('/api/crawler/' + id).then(response => {
            this.setState({
                displayClass: '',
                display: true,
                id: id,
                title: response.data.title,
                url: response.data.url,
                screenShotUrl: response.data.screenShotPath,
                description: response.data.description,
                body: response.data.body,
                createdAt: response.data.createdAt,
            })
        }).catch(error => {
            alert(error.message)
        })
    }

    render () {
        return (
            <div className={ this.state.displayClass }>
                <div>Origin link: <a href={ this.state.url } target="_blank">{ this.state.title }</a></div>
                <a href={'/crawler/' + this.state.id } target="_blank" className={ this.state.displayDetailLinkClass }>
                    Detail page link
                </a>
                <div>Description: { this.state.description ?? '-' } </div>
                <div>Crated At: { this.state.createdAt ?? '-' }</div>
                <div>Screenshot:</div>
                <img src={ this.state.screenShotUrl } alt=""/>
                <div className={ this.state.displayBodyClass }>
                    <div>Body</div>
                    <div>
                        { this.state.body }
                    </div>
                </div>
            </div>
        )
    }
}

export default withRouter(CrawlerShow)
