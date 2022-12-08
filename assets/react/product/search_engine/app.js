import React from "react";
import {createRoot} from "react-dom/client";
import SearchResults from "./components/search_results";

const root = createRoot(document.getElementById('product-search-engine-results'));
root.render(
    <div>
        <SearchResults/>
    </div>
);


/* Do zrobienia po stronie reactu wyrenderowanie formularza, to nie powinno być trudne jeśli chodzi o sam widok */
/* Wysyłanie formularza do endpointu, tutaj fetch czy axios? */
/* Samemu trzeba renderować walidacje itp... czy to ma sens z tym formularzem? Nie za dużo pracy */
/* Na jakich stronach to ma jakiś sens? */