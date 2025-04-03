import { Product } from '@/types/index';
import { Link } from '@inertiajs/react';
import React from 'react';
import CurrencyFormatter from './CurrencyFormatter';

interface ProductItemProps {
  product: Product;
}

const ProductItem: React.FC<ProductItemProps> = ({ product }) => {
  return (
    <div>
      <div>
        <Link href={route('product.show', product.slung)}>
          <figure>
            <img src={product.image} alt={product.title} className='aspect-square object-cover' />
          </figure>
        </Link>
        <div className="card-body">
          <h2 className='card-title'>{product.title}</h2>
          <p>
            by <Link href="/" className="hover:underline">{product.user.name}</Link>&nbsp;
            in <Link href="/" className="hover:underline">{product.department.name}</Link>
          </p>
          <div className='card-actions item-center justify-between mt-3'>
            <button className='btn btn-primary'>Add to Cart</button>
            <span className='text-2xl'>
              <CurrencyFormatter amount={product.price} />
            </span>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ProductItem;


//
// import { Product } from '@/types/index'
// import { Link } from '@inertiajs/react'
// import React from 'react'
// import CurrencyFormatter from './CurrencyFormatter'

// function productItem(product:{product: Product}) {
//   return (
//     <div>
//       <div>
//         <Link href={route('product.show', product.slung)}>
//           <figure>
//             <img src={product.image} alt={product.title} className='aspect-square object-cover'/>
//           </figure>
//         </Link>
//         <div className="card-body">
//           <h2 className='card-title'>{product.title}</h2>
//           <p>
//             by <Link href="/" className="hover:underline">{product.user.name }</Link>&nbsp;
//             in <Link href="/" className="hover:underline">{product.department.name }</Link>
//           </p>
//           <div className='card-actions item-center justify-between mt-3'>
//             <button className='btn btn-primary'>Add to Cart</button>
//             <span className='text-2xl'>
//               <CurrencyFormatter amount={product.price} />
//             </span>
//           </div>
//         </div>
//       </div>
//     </div>
//   )
// }

// export default productItem

