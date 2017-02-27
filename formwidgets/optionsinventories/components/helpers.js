export const inventoryCollsionCheck = function(inventory, inventories) {
    // helper to create a sorted signature for an inventory
    let getValueSignature = function(inv) {
        return typeof inv.value_ids !== 'undefined'
            ? inv.value_ids.sort().join('.')
            : inv.values.map(value => value.id).sort().join('.');
    };

    let valueSignature = getValueSignature(inventory);
    let otherInventories = inventories.filter(inv => inv.id !== inventory.id && ! inv._deleted);

    return inventories
        .filter(inv => inv.id !== inventory.id && ! inv._deleted)
        .find(inv => getValueSignature(inv) === valueSignature);
};
