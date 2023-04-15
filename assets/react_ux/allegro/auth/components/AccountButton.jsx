import React from 'react';

const AccountButton = function (props) {
    const {id, name, deviceCodeActive, refreshTokenActive} = props.allegroAccount;

    function handleAuthorization(event) {
        event.preventDefault();

        const button = event.target;
        const allegroAccountId = button.dataset.accountId;

        fetch (`/allegro/auth/authorize/account/${allegroAccountId}`)
            .then((response) => {
                return response.json();
            })
            .then((auth) => {
                if (auth.status === 'error') {
                    alert('Coś poszło nie tak podczas pobierania tokenów. Proszę zgłosić błąd.');

                    return;
                }

                window.open(auth.verification_uri, '_blank');
                props.onShownLink(allegroAccountId);
            })
    }

    function handleFetchingTokens(event) {
        event.preventDefault();

        const button = event.target;
        const allegroAccountId = button.dataset.accountId;

        fetch (`/allegro/auth/get/refresh/token/${allegroAccountId}`)
            .then((response) => {
                return response.json();
            })
            .then((response) => {
                if (response.status === 'error') {
                    alert('Coś poszło nie tak podczas pobierania tokenów. Proszę zgłosić błąd.');

                    return;
                }

                props.onFetchedToken(allegroAccountId);
            })
    }

    return (
        <article className="card col-sm-12 col-md-6">
            <div className="card-body">
                <h3 className="text-center mb-3">{name}</h3>
                { refreshTokenActive ?
                    <div className="alert alert-success">
                        To konto jest skonfigurowane!
                    </div> : null
                }
                { !refreshTokenActive && deviceCodeActive ?
                    <div>
                        <div className="alert alert-success">
                            Kod powiązania z allegro jest aktywny, w ciągu godziny od jego aktywowania możesz wygenerować
                            token do komunikacji z allegro. Tą czynność musisz wykonać tylko raz.
                        </div>
                        <button className="fetch-refresh-token btn btn-warning me-1" data-account-id={id}
                                onClick={handleFetchingTokens}>
                            Wygeneruj token
                        </button>
                    </div> : null
                }
                { !refreshTokenActive && !deviceCodeActive ?
                    <div>
                        <div className="alert alert-success">
                            Powiąż naszą aplikację ze swoim kontem allegro.
                        </div>
                        <button className="authorize-allegro-account btn btn-success me-1" data-account-id={id}
                                onClick={handleAuthorization}>
                           Powiąż konto
                        </button>
                    </div> : null
                }
            </div>
        </article>
    );
}

export default AccountButton;