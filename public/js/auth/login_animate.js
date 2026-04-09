
tailwind.config = {
    theme: {
        extend: {
            animation: {
                'fade-in': 'fadeIn 2s ease-out forwards',
                'slide-in-left': 'slideInLeft 0.8s ease-out forwards',
                'slide-in-right': 'slideInRight 0.8s ease-out forwards',
            },
            keyframes: {
                fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                slideInLeft: { '0%': { transform: 'translateX(-50px)', opacity: '0' }, '100%': { transform: 'translateX(0)', opacity: '1' } },
                slideInRight: { '0%': { transform: 'translateX(50px)', opacity: '0' }, '100%': { transform: 'translateX(0)', opacity: '1' } },
            },
        },
    },
}
