export default {
    // xhr stubs
    delete: sinon.stub(),
    get: sinon.stub(),
    post: sinon.stub(),
    put: sinon.stub(),

    // reset the assertions on our stubs
    reset() {
        this.delete.reset();
        this.get.reset();
        this.post.reset();
        this.put.reset();
    },
};
