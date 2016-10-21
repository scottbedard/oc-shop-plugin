module.exports = {
    '**/bedard/shop/assets/{manifest,vendor}.js': {
        target: '/',
        secure: false,
    },
    '!**/bedard/shop/assets/**/*.{css,js,hot-update.json}': {
        target: 'http://beeasyboards.dev',
        secure: false,
    },
};
