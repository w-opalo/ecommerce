import { PaginationProps, Product, type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import Navbar from '@/components/ui/navbar';
import ProductItem from '@/components/ui/productItem';

export default function Welcome({ products }: { products: PaginationProps<Product> }) {
    const { auth } = usePage<SharedData>().props;

    return (
        <>
            <Head title="Welcome">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>

            <Navbar />

            <div className="hero bg-gray-200 h-[300px]">
                <div className="hero-content text-center">
                    <div className="max-w-md">
                    <h1 className="text-5xl font-bold">Hello there</h1>
                    <p className="py-6">
                        Provident cupiditate voluptatem et in. Quaerat fugiat ut assumenda excepturi exercitationem
                        quasi. In deleniti eaque aut repudiandae et a id nisi.
                    </p>
                    <button className="btn btn-primary">Get Started</button>
                    </div>
                </div>
            </div>

            <div className='grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3 p-8'>
                {products.data.map(product => (
                    <ProductItem product={product } key={product.id } />
                ))}
            </div>
        </>
    );
}
