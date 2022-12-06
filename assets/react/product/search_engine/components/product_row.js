import React from 'react';

const ProductRow = function (props) {
    return (
        <tr>
            <td>{props.product.id}</td>
            <td>{props.product.name}</td>
        </tr>
    );
};

export default ProductRow;