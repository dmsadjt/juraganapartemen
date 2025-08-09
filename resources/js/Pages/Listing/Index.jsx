import React, { useState } from 'react';
import '../../../css/app.css';
import AppLayout from '../Layouts/AppLayout';
import { Head } from '@inertiajs/react';

export default function Index({ units }) {
    return (
        <AppLayout>
            <Head title='Listings' />

            <div>
                <h1>Listings</h1>
                <ul>
                    {units.map(unit => (
                        <li key={unit.id}>
                            <h2>{unit.name}</h2>
                            <p>{unit.description}</p>
                            <br />
                        </li>
                    ))}
                </ul>
            </div>
        </AppLayout>
    );
}