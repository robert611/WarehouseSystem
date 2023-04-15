import React, {Component} from 'react';
import ProductRow from "./product_row";

class SearchResults extends Component {
    constructor(props) {
        super(props);
        this.state = {
            products: [],
        };
        this.handleFormSubmit = this.handleFormSubmit.bind(this);
    }

    handleFormSubmit(event) {
        event.preventDefault();
        const form = document.getElementById('product-search-engine-form');
        const endpoint = form.getAttribute('action');
        const options = {
            'method': 'POST',
            'body': new FormData(form),
        };
        fetch(endpoint, options)
            .then((response) => {
                return response.json();
            })
            .then((response) => {
                this.setState({
                   products: response.products,
                });
            });
    }

    componentDidMount() {
        const searchButton = document.getElementById('product-search-engine-form-button');
        searchButton.addEventListener('click', this.handleFormSubmit);
    }

    render() {
        const {products} = this.state;
        const productRows = products.map((product, index) => {
            return <ProductRow key={product.id} product={product} index={index}></ProductRow>
        });

        return (
            <div className="table-responsive">
                <table className="table table-bordered">
                    <thead>
                    <tr>
                        <th>Lp.</th>
                        <th>Identifier</th>
                        <th>Name</th>
                        <th>Typ sprzedaży</th>
                        <th>Licytacja</th>
                        <th>Kup teraz</th>
                        <th>Użytkownik</th>
                    </tr>
                    </thead>
                    <tbody>
                        {productRows}
                    </tbody>
                </table>
            </div>
        );
    }
}

export default SearchResults;
