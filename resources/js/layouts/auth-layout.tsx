import AuthLayoutTemplate from '@/layouts/auth/auth-simple-layout';
// import navbar from '@/components/ui/Navbar';

export default function AuthLayout({ children, title, description, ...props }: { children: React.ReactNode; title: string; description: string }) {
    return (
        <AuthLayoutTemplate title={title} description={description} {...props}>
            {children}
        </AuthLayoutTemplate>
    );
}
