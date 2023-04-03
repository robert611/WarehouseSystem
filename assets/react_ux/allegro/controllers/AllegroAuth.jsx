import React, {Component} from 'react'
import AccountButton from "../auth/components/AccountButton";

class AllegroAuth extends Component {
    constructor(props) {
        super(props);
        this.state = {
            allegroAccounts: props.allegroAccounts,
        };
    }

    render() {
        const {allegroAccounts} = this.state;
        const allegroAccountsButtons = allegroAccounts.map((allegroAccount, index) => {
            return <AccountButton key={index} allegroAccount={allegroAccount}></AccountButton>;
        });

        console.log(allegroAccounts);

        return (
            <div className="allegro-auth-accounts mx-5 mt-4">
                <h2>Konta allegro</h2>
                {allegroAccountsButtons}
            </div>
        );
    }
}

export default AllegroAuth;