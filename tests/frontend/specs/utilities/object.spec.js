import { camelCaseKeys, snakeCaseKeys } from 'assets/js/utilities/object';

describe.only('object utilities', () => {
    const testObject = {
        camelCase: true,
        snake_case: true,
        array: [{
            camelCase: true,
            snake_case: true,
        }],
        child: {
            camelCase: true,
            snake_case: true,
        },
    };

    it('camelCaseKeys', () => {
        expect(camelCaseKeys(testObject)).to.deep.equal({
            camelCase: true,
            snakeCase: true,
            array: [{
                camelCase: true,
                snakeCase: true,
            }],
            child: {
                camelCase: true,
                snakeCase: true,
            },
        });
    });

    it('snakeCaseKeys', () => {
        expect(snakeCaseKeys(testObject)).to.deep.equal({
            camel_case: true,
            snake_case: true,
            array: [{
                camel_case: true,
                snake_case: true,
            }],
            child: {
                camel_case: true,
                snake_case: true,
            },
        });
    });
});
