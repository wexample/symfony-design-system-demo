<script>
import BlockList from '@wexample/symfony-design-system/components/block-list/block-list.vue';
import Marker from '@wexample/symfony-design-system/components/marker/marker.vue';

// What a block list asks of its caller: answer `move` and `remove`, add on
// its own buttons, open what it just added. Here the caller is a plain array
// standing in for an api.
export default {
  template: '#vue-template-wexample-symfony-design-system-demo-bundle-components-demo-block-list-demo-block-list',

  components: {
    BlockList,
    DsMarker: Marker
  },

  data() {
    return {
      steps: [
        { id: 1, kind: 'fetch', name: 'Read the request', source: 'request' },
        { id: 2, kind: 'transform', name: 'Keep the last 10', source: 'history' },
        { id: 3, kind: 'send', name: 'Answer', source: 'model' }
      ],
      nextId: 4,
      openKey: null
    };
  },

  methods: {
    onMove(step, direction, to) {
      const from = this.steps.indexOf(step);

      this.steps.splice(from, 1);
      this.steps.splice(to, 0, step);
    },

    onRemove(step) {
      this.steps = this.steps.filter((other) => other !== step);
    },

    add(kind) {
      const step = { id: this.nextId++, kind, name: '', source: 'request' };

      this.steps.push(step);
      this.openKey = step.id;
    }
  }
};
</script>
