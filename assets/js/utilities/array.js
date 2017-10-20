/**
 * Determine the intersection of two arrays.
 *
 * @param  {Array} arr1
 * @param  {Array} arr2
 * @return {Array}
 */
export function intersection(arr1, arr2) {
    return arr1.filter(val => arr2.includes(val));
}

/**
 * Test that two arrays have the same members, regardless of order.
 *
 * @param  {Array}  arr1
 * @param  {Array}  arr2
 * @return {Boolean}
 */
export function hasSameMembers(arr1, arr2) {
    return JSON.stringify(arr1.concat().sort()) === JSON.stringify(arr2.concat().sort());
}
