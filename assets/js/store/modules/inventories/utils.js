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

// test that value ids are unique
export function valuesAreTaken(newInventory, state) {
    return !!state.inventories.find(inventory => {
        return inventory._key !== newInventory._key
            && hasSameMembers(newInventory.valueKeys, inventory.valueKeys);
    });
}

// validate an inventory
export function validateInventory(inventory, state) {
    return new Promise((resolve, reject) => {

        // skus must be locally unique
        if (skuIsTakenLocally(inventory, state)) {
            return reject('bedard.shop.inventories.form.sku_unique_error');
        }

        // the quantity must be at least zero
        if (inventory.quantity < 0) {
            return reject('bedard.shop.inventories.form.quantity_negative_error');
        }

        // there can only be one default inventory
        if (defaultIsTaken(inventory, state)) {
            return reject('bedard.shop.inventories.form.default_exists_error');
        }

        // the value ids must be unique
        if (valuesAreTaken(inventory, state)) {
            return reject('bedard.shop.inventories.form.value_collision_error');
        }

        // store: no other inventories with same value keys
        // store: quantity >= 0
        // backend: no other inventories with same sku

        resolve();
    });
}
