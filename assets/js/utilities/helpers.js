/**
 * Clone an object,
 *
 * @param  {Object} obj
 * @return {Object}
 */
export const clone = function(obj) {
    return JSON.parse(JSON.stringify(obj));
};

/**
 * Rename an object key.
 *
 * @param  {Object} obj
 * @param  {String} oldName
 * @param  {String} newName
 * @return {Object}
 */
export const renameKey = function(obj, oldName, newName) {
    obj[newName] = clone(obj[oldName]);
    delete obj[oldName];

    return obj;
};
