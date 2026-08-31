import React from 'react';

export default function GeneratedLink({value}) {
    if (!value) {
        return null;
    }

    return (
        <a href={value} rel="noreferrer" target="_blank">
            {value}
        </a>
    );
}
