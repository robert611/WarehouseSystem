import React, {Component} from 'react'
import AccountButton from "../auth/components/AccountButton";

class AllegroAuth extends Component {
    constructor(props) {
        super(props);
        this.state = {
            allegroAccounts: props.allegroAccounts,
        };
        this.onFetchedToken.bind(this);
    }

    onFetchedToken = (allegroAccountId) => {
        let allegroAccounts = this.state.allegroAccounts.map((element) => {
            if (element.id === parseInt(allegroAccountId)) {
                element.refreshTokenActive = true;
            }

            return element;
        })
        this.setState({allegroAccounts: allegroAccounts});
    }

    onShownLink = (allegroAccountId) => {
        let allegroAccounts = this.state.allegroAccounts.map((element) => {
            if (element.id === parseInt(allegroAccountId)) {
                element.refreshTokenActive = false;
                element.deviceCodeActive = true;
            }

            return element;
        })
        this.setState({allegroAccounts: allegroAccounts});
    }

    render() {
        const {allegroAccounts} = this.state;
        const allegroAccountsButtons = allegroAccounts.map((allegroAccount, index) => {
            return <AccountButton key={index} allegroAccount={allegroAccount}
                    onFetchedToken={this.onFetchedToken} onShownLink={this.onShownLink}
            />
        });

        return (
            <div className="allegro-auth-accounts mx-5 mt-4">
                <h2>Konta allegro</h2>
                <hr/>
                <div className="row">
                    {allegroAccountsButtons}
                </div>
            </div>
        );
    }
}

export default AllegroAuth;