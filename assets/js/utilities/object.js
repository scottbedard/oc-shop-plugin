import camel from 'camel-case';
import snake from 'snake-case';

/**
 * Camel case all the keys of an object.
 *
 * @param  {Object} obj     the object being camel cased
 * @return {Object}
 */
export function camelCaseKeys(obj) {
    return Object.keys(obj).reduce((newObj, key) => {
        if (key[0] === '_') {
            newObj[key] = obj[key];
        } else if (Array.isArray(obj[key])) {
            newObj[camel(key)] = obj[key].map(camelCaseKeys);
        } else if (typeof obj[key] === 'object' && obj[key] !== null) {
            newObj[camel(key)] = camelCaseKeys(obj[key]);
        } else {
            newObj[camel(key)] = obj[key];
        }

        return newObj;
    }, {});
}

/**
 * Snake case all the keys of an object.
 *
 * @param  {Object} obj     the object being snake cased
 * @return {Object}
 */
export function snakeCaseKeys(obj) {
    return Object.keys(obj).reduce((newObj, key) => {
        if (key[0] === '_') {
            newObj[key] = obj[key];
        } else if (Array.isArray(obj[key])) {
            newObj[snake(key)] = obj[key].map(snakeCaseKeys);
        } else if (typeof obj[key] === 'object' && obj[key] !== null) {
            newObj[snake(key)] = snakeCaseKeys(obj[key]);
        } else {
            newObj[snake(key)] = obj[key];
        }

        return newObj;
    }, {});
}
