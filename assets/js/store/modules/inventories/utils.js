import axios from 'axios';
import { snakeCaseKeys } from 'assets/js/utilities/object';
import { hasSameMembers } from 'assets/js/utilities/array';

// test if a default inventory is already taken
export function defaultIsTaken(newInventory, state) {
    return newInventory.valueKeys.length === 0 && !!state.inventories.find(inventory => {
        return inventory.valueKeys.length === 0 && inventory._key !== newInventory._key;
    });
}

// test if a sku already exists within our inventories array
export function skuIsTakenLocally(newInventory, state) {
    return newInventory.sku && !!state.inventories.find((inventory) => {
        return inventory._key !== newInventory._key
            && inventory.sku === newInventory.sku;
    });
}

// test if a sku is taken on the server
export function validateInventoryOnServer(newInventory, state) {
    return axios.post(state.endpoints.validateInventory, snakeCaseKeys(newInventory));
}

// test that value ids are unique
export function valuesAreTaken(newInventory, state) {
    return !!state.inventories.find(inventory => {
        return inventory._key !== newInventory._key
            && hasSameMembers(newInventory.valueKeys, inventory.valueKeys);
    });
}

// validate an inventory
export function validateInventory(inventory, state) {
    // skus must be locally unique
    if (skuIsTakenLocally(inventory, state)) {
        return Promise.reject({
            response: { data: 'bedard.shop.inventories.form.sku_unique_local_error' },
        });
    }

    // the quantity must be at least zero
    if (inventory.quantity < 0) {
        return Promise.reject({
            response: { data: 'bedard.shop.inventories.form.quantity_negative_error' },
        });
    }

    // there can only be one default inventory
    if (defaultIsTaken(inventory, state)) {
        return Promise.reject({
            response: { data: 'bedard.shop.inventories.form.default_exists_error' },
        });
    }

    // the value ids must be unique
    if (valuesAreTaken(inventory, state)) {
        return Promise.reject({
            response: { data: 'bedard.shop.inventories.form.value_collision_error' },
        });
    }

    // fun any async validations neccessary
    const asyncValidators = [];
    asyncValidators.push(validateInventoryOnServer(inventory, state));

    return Promise.all(asyncValidators);
}
