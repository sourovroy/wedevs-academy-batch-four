import domReady from '@wordpress/dom-ready';
import { createRoot } from '@wordpress/element';

import { createHashRouter, RouterProvider, Outlet, Link } from "react-router-dom";

import './main.scss';

function SettingsPage() {
    return (
        <div>
            <h1>React Settings</h1>
            <ul className="abfp-settings-menu">
                <li>
                    <Link to="/">Overview</Link>
                </li>
                <li>
                    <Link to="/settings">Settings</Link>
                </li>
            </ul>
            <div className="abfp-pages-container">
                <Outlet />
            </div>
        </div>
    );
}

function Overview() {
    return (
        <div>
            This is overview page.
        </div>
    );
}


function Settings() {
    return (
        <div>
            This is Settings page.
        </div>
    );
}

const router = createHashRouter([
    {
        path: "/",
        element: <SettingsPage />,
        children: [
            {
                path: "",
                element: <Overview />,
            },
            {
                path: "/settings",
                element: <Settings />,
            },
        ],
    }
]);

domReady(() => {
    const root = createRoot( document.getElementById( 'abfp-react-settings-app' ) );

    root.render( <RouterProvider router={router} /> );
});
