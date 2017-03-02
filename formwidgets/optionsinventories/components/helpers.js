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

export const inventoryHasDeletedRelation = function(inventory, options) {
    // if any of our values are being deleted, return true
    if (inventory.values.find(value => value._deleted)) {
        return true;
    }

    // loop through our option ids and see if we find one being deleted
    for (let id of inventory.values.map(value => value.option_id)) {
        let option = options.find(opt => opt.id == id);

        // if one was found, return true
        if (option && option._deleted) {
            return true;
        }
    }

    return false;
};
