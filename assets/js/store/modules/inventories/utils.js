// test if a sku already exists locally
export function skusAreNotLocallyUnique(newInventory, state) {
    return !!state.inventories
        .filter(inventory => inventory._key !== newInventory._key)
        .filter(inventory => inventory.sku === newInventory.sku)
        .length;
}

// validate an inventory
export function validateInventory(inventory, state) {
    return new Promise((resolve, reject) => {
        // skus must be locally unique
        if (skusAreNotLocallyUnique(inventory, state)) {
            return reject('bedard.shop.inventories.form.sku_unique_error');
        }

        // store: no other inventories with same value keys
        // store: quantity >= 0
        // backend: no other inventories with same sku

        resolve();
    });
}
