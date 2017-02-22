// http://eslint.org/docs/user-guide/configuring
module.exports = {
    env: {
        browser: true,
    },
    parser: 'babel-eslint',
    parserOptions: {
        sourceType: 'module',
    },
    plugins: [
        'html', // <- required to lint *.vue files
    ],
    root: true,
    rules: {
        'arrow-spacing': ['error', { before: true, after: true }],
        'comma-dangle': ['error', 'always-multiline'],
        'indent': ['error', 4],
        'keyword-spacing': ['error', { before: true, after: true }],
        'no-debugger': process.env.NODE_ENV === 'production' ? 2 : 0, // allow debugger in development
        'quotes': ['error', 'single'],
        'semi': ['error', 'always'],
        'space-before-blocks': ['error', { functions: 'always', keywords: 'always' }],
        'space-before-function-paren': ['error', 'never'],
    },
};
