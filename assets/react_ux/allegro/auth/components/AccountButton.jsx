import React from 'react';

const AccountButton = function (props) {
    const {id, name} = props.allegroAccount;

    async function handleAuthorization(event) {
        event.preventDefault();

        const button = event.target;
        const allegroAccountId = button.dataset.accountId;

        fetch('/allegro/auth/authorize/account/' + allegroAccountId)
            .then((response) => {
                return response.json();
            })
            .then((auth) => {
                const link = 'https://allegro.pl/auth/oauth/authorize?' +
                    'response_type=' + auth.response_type +
                    '&client_id=' + auth.client_id +
                    '&redirect_uri=' + auth.redirect_uri ;

                window.location = link;
            })
    }

    return (
        <button className="authorize-allegro-account btn btn-success" data-account-id={id} onClick={handleAuthorization}>
            {name}
        </button>
    );
}

export default AccountButton;