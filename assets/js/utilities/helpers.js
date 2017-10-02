/**
 * Clone an object,
 *
 * @param  {Mixed}  obj
 * @return {Object}
 */
export const clone = function(obj) {
    return typeof obj === 'object'
        ? JSON.parse(JSON.stringify(obj))
        : obj;
};

/**
 * Rename an object key.
 *
 * @param  {Object} object
 * @param  {String} oldName
 * @param  {String} newName
 * @return {Object}
 */
export const renameKey = function(object, oldName, newName) {
    if (typeof object !== 'object') {
        throw `Cannot rename property of a non-object, ${ typeof object } given`;
    }

    const newObject = clone(object);
    Object.defineProperty(newObject, newName, Object.getOwnPropertyDescriptor(newObject, oldName));
    delete newObject[oldName];

    return newObject;
};

/**
 * Return a unique integer.
 *
 * @type {Number}
 */
let uniqueIdCount = 0;
export const uniqueId = () => uniqueIdCount++;
