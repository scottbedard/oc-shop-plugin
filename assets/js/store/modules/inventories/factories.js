import { uniqueId } from 'assets/js/utilities/helpers';

/**
 * Create an inventory.
 *
 * @param  {Object} options
 * @return {Object}
 */
export function createInventory(options = {}) {
    return {
        _delete: false,
        _key: uniqueId(),
        id: null,
        quantity: 0,
        sku: null,
        values: [],
        ...options,
    };
}

/**
 * Create an option.
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
 * Create an option value.
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
