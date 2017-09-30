describe('<v-button>', () => {
    it('render a button', () => {
        vm = mount({
            template: '<v-button>Hello</v-button>',
        });

        expect(vm.$el.tagName).to.equal('BUTTON');
        expect(vm.$el.textContent).to.equal('Hello');
    });

    it('emits a click event', () => {
        const onClick = sinon.spy();

        vm = mount({
            methods: { onClick },
            template: '<v-button @click="onClick" />',
        });

        click(vm.$el);

        expect(onClick).to.have.been.called;
    });

    it('accepts a primary prop', (done) => {
        vm = mount({
            data: () => ({ primary: false }),
            template: '<v-button :primary="primary" />',
        });

        expect(vm.$el.classList.contains('btn-default')).to.be.true;
        expect(vm.$el.classList.contains('btn-primary')).to.be.false;

        vm.primary = true;
        vm.$nextTick(() => {
            expect(vm.$el.classList.contains('btn-default')).to.be.false;
            expect(vm.$el.classList.contains('btn-primary')).to.be.true;

            done();
        });
    });
});
