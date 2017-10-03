import snakeCase from 'snake-case';

/**
 * Clone an object,
 *
 * @param  {Mixed}  obj
 * @return {Object}
 */
export function clone(obj) {
    return typeof obj === 'object'
        ? JSON.parse(JSON.stringify(obj))
        : obj;
};

/**
 * Determine if a variable is a standard object. A "standard"
 * object is an object that is not an array or null.
 *
 * @param  {mixed}      value
 * @return {Boolean}
 */
export function isStandardObject(value) {
    return typeof value === 'object'
        && !Array.isArray(value)
        && value !== null;
}

/**
 * Rename an object key.
 *
 * @param  {Object} object
 * @param  {String} oldName
 * @param  {String} newName
 * @return {Object}
 */
export function renameKey(object, oldName, newName) {
    if (typeof object !== 'object') {
        throw `Cannot rename property of a non-object, ${ typeof object } given`;
    }

    const newObject = clone(object);
    Object.defineProperty(newObject, newName, Object.getOwnPropertyDescriptor(newObject, oldName));
    delete newObject[oldName];

    return newObject;
};

/**
 * Create an object with snake cased keys.
 *
 * @param  {Object} obj
 * @return {Object}
 */
export function snakeCaseKeys(obj) {
    const result = {};

    Object.keys(obj).forEach((key) => {
        if (key.startsWith('_')) {
            result[key] = obj[key];
        } else {
            result[snakeCase(key)] = obj[key];
        }
    });

    return result;
}

/**
 * Create an object with deep snake case keys.
 *
 * @param  {Object} obj
 * @return {Object}
 */
export function snakeCaseKeysDeep(obj) {
    if (Array.isArray(obj)) {
        return obj.map(snakeCaseKeysDeep);
    } else if (!isStandardObject(obj)) {
        return obj;
    }

    const result = {};

    Object.keys(obj).forEach((key) => {
        if (key.startsWith('_')) {
            result[key] = obj[key];
        } else if (Array.isArray(obj[key])) {
            result[snakeCase(key)] = obj[key].map(snakeCaseKeysDeep);
        } else {
            result[snakeCase(key)] = isStandardObject(obj[key])
                ? snakeCaseKeysDeep(obj[key])
                : obj[key];
        }
    });

    return result;
}

/**
 * Return a unique integer.
 *
 * @type {Number}
 */
let uniqueIdCount = 0;
export const uniqueId = () => uniqueIdCount++;
