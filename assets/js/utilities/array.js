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
