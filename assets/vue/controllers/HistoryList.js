import { h } from 'vue';

export default {
    props: {
        histories: Array
    },
    render(ctx) {
        return h('div', [
            h('h3', 'History List'),
            h('ul', ctx.histories.map(item =>
                h('li', { key: item[0] }, [
                    // A single <p> tag containing both item[1] and item[2]
                    h('p', [
                        h('strong', item[1]),
                        h('br'),
                        item[2]
                    ])
                ])
            ))
        ]);
    }
}
