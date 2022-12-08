import React from 'react';

const ProductRow = function (props) {
    const {saleType, id, name, auctionPrice, buyNowPrice} = props.product;

    return (
        <tr>
            <td>{props.index + 1}</td>
            <td>{id}</td>
            <td>{name}</td>
            <td>{saleType.name}</td>
            <td>
                {(saleType.type === 2 || saleType.type === 3) ?
                    <span>
                        {auctionPrice ? auctionPrice + ' zł' : 'Brak'}
                    </span>
                : 'Brak'}
            </td>
            <td>
                {(saleType.type === 1 || saleType.type === 3) ?
                    <span>
                        {buyNowPrice ? buyNowPrice + ' zł' : 'Brak'}
                    </span>
                : 'Brak'}
            </td>
            <td>{props.product.user.username}</td>
        </tr>
    );
};

export default ProductRow;