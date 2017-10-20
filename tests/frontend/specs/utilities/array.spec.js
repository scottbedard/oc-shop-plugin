import {
    intersection,
    hasSameMembers,
} from 'assets/js/utilities/array';

describe('array utilities', () => {
    it('intersection', () => {
        expect(intersection([1, 2, 3], [2, 3, 4])).to.deep.equal([2, 3]);
        expect(intersection([1, 2, 3], [4, 5, 6])).to.deep.equal([]);
    });

    it('hasSameMembers', () => {
        expect(hasSameMembers([1, 2, 3], [3, 2, 1])).to.be.true;
        expect(hasSameMembers([1, 2, 3], [4, 5, 6])).to.be.false;
        expect(hasSameMembers([1, 2, 3, 4], [1, 2, 3])).to.be.false;
        expect(hasSameMembers([1, 2, 3], [1, 2, 3, 4])).to.be.false;
    });
});
