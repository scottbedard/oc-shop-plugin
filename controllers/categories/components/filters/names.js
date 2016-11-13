export default {
    left: {
        base_price: 'base_price',
        created_at: 'left_created_at',
        price: 'actual_price',
        updated_at: 'left_updated_at',
    },
    comparator: {
        '<': 'comparator_less_than',
        '<=': 'comparator_less_than_or_equal',
        '<>': 'comparator_not_equal_to',
        '=': 'comparator_equal_to',
        '>': 'comparator_greater_than',
        '>=': 'comparator_greater_than_or_equal',
    },
    right: {
        base_price: 'base_price',
        custom: 'right_custom',
        price: 'actual_price',
    },
};
