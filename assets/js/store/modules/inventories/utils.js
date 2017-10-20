// test if a sku already exists locally
export function skusAreNotLocallyUnique(newInventory, state) {
    return !!state.inventories.find((inventory) => {
        return inventory.sku
            && inventory.sku === newInventory.sku
            && inventory._key !== newInventory.key;
    });
}

// validate an inventory
export function validateInventory(inventory, state) {
    return new Promise((resolve, reject) => {
        // skus must be locally unique
        if (skusAreNotLocallyUnique(inventory, state)) {
            reject('bedard.shop.inventories.form.sku_unique');
        }

        // store: no other inventories with same value keys
        // store: quantity >= 0
        // backend: no other inventories with same sku

        resolve();
    });
}
