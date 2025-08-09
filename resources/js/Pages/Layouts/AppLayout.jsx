import React from 'react';

export default function AppLayout({ children }) {
    return (
        <div>
            <header>
                <h1>juraganapartemen.com</h1>
            </header>

            <main>{children}</main>
        </div>
    )
}