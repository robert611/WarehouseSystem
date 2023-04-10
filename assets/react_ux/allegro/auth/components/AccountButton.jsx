import React from 'react';

const AccountButton = function (props) {
    const {id, name} = props.allegroAccount;

    async function handleCodeAuthorization(event) {
        event.preventDefault();

        const button = event.target;
        const allegroAccountId = button.dataset.accountId;

        fetch(`/allegro/auth/code/authorize/account/${allegroAccountId}`)
            .then((response) => {
                return response.json();
            })
            .then((auth) => {
                window.location = auth.auth_url + '?' +
                    'response_type=' + auth.response_type +
                    '&client_id=' + auth.client_id +
                    '&redirect_uri=' + auth.redirect_uri;
            })
    }

    async function handleDeviceAuthorization(event) {
        event.preventDefault();

        const button = event.target;
        const allegroAccountId = button.dataset.accountId;

        fetch (`/allegro/auth/device/authorize/account/${allegroAccountId}`)
            .then((response) => {
                return response.json();
            })
            .then((auth) => {
                window.location = auth.verification_uri;
            })
    }

    return (
        <button className="authorize-allegro-account btn btn-success me-1" data-account-id={id}
                onClick={handleDeviceAuthorization}>
            {name}
        </button>
    );
}

export default AccountButton;