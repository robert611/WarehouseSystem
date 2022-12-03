import React from "react";
import {createRoot} from "react-dom/client";
import SearchForm from "./components/search_form";

const root = createRoot(document.getElementById('product-search-engine-app'));
root.render(
    <div>
        <SearchForm/>
    </div>
);


/* Do zrobienia po stronie reactu */