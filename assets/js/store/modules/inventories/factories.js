import { uniqueId } from 'assets/js/utilities/helpers';

/**
 * Create a new option
 *
 * @param  {Object} options
 * @return {Object}
 */
export function createOption(options = {}) {
    return {
        _delete: false,
        _key: uniqueId(),
        id: null,
        name: '',
        placeholder: '',
        sortOrder: 0,
        values: [],
        ...options,
    };
}

/**
 * Create a new option value.
 *
 * @param  {Object} options
 * @return {Object}
 */
export function createOptionValue(options = {}) {
    return {
        _delete: false,
        _key: uniqueId(),
        id: null,
        name: '',
        sortOrder: 0,
        ...options,
    };
}
