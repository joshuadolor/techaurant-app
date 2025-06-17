<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 60" fill="none">
        <!-- Background rectangle with rounded corners -->
        <rect x="10" y="10" width="180" height="40" rx="8" fill="#2C5282" />

        <!-- Atom icon (left side) -->
        <g transform="translate(30, 30)">
            <!-- Central circle -->
            <circle cx="0" cy="0" r="8" fill="#F6AD55" />

            <!-- Orbital rings -->
            <ellipse
                cx="0"
                cy="0"
                rx="12"
                ry="6"
                stroke="#F6AD55"
                stroke-width="2"
                transform="rotate(0)"
                opacity="0.8"
            />
            <ellipse
                cx="0"
                cy="0"
                rx="12"
                ry="6"
                stroke="#F6AD55"
                stroke-width="2"
                transform="rotate(60)"
                opacity="0.8"
            />
            <ellipse
                cx="0"
                cy="0"
                rx="12"
                ry="6"
                stroke="#F6AD55"
                stroke-width="2"
                transform="rotate(120)"
                opacity="0.8"
            />

            <!-- Connection points -->
            <circle cx="12" cy="0" r="2" fill="#F6AD55" />
            <circle cx="-12" cy="0" r="2" fill="#F6AD55" />
            <circle cx="6" cy="10.4" r="2" fill="#F6AD55" />
            <circle cx="-6" cy="-10.4" r="2" fill="#F6AD55" />
            <circle cx="6" cy="-10.4" r="2" fill="#F6AD55" />
            <circle cx="-6" cy="10.4" r="2" fill="#F6AD55" />
        </g>

        <!-- App name text (right side) -->
        <text
            x="100"
            y="37"
            font-family="Arial, sans-serif"
            font-size="22"
            font-weight="bold"
            fill="white"
            text-anchor="middle"
            letter-spacing="0.5"
        >
            TechnoResto
        </text>

        <!-- Subtle background pattern -->
        <path
            d="M160 15 L180 45"
            stroke="#F6AD55"
            stroke-width="2"
            opacity="0.1"
        />
        <path
            d="M165 15 L185 45"
            stroke="#F6AD55"
            stroke-width="2"
            opacity="0.1"
        />
    </svg>
</template>
