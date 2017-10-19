import { intersection } from 'assets/js/utilities/array';

describe('array utilities', () => {
    it('intersection', () => {
        expect(intersection([1, 2, 3], [2, 3, 4])).to.deep.equal([2, 3]);
        expect(intersection([1, 2, 3], [4, 5, 6])).to.deep.equal([]);
    });
});
