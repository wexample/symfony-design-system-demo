<script>
import Distribution from '@wexample/symfony-design-system/components/distribution/distribution.vue';

// What a distribution looks like from the outside: parts given as data, and a
// click on one answered with `select`. Nothing here computes a share — that is
// the component's job, and the point of it.
export default {
  template: '#vue-template-wexample-symfony-design-system-demo-bundle-components-demo-distribution-demo-distribution',

  components: {
    Distribution
  },

  data() {
    return {
      segments: [
        { value: 12, label: 'Done', type: 'success' },
        { value: 5, label: 'Running', type: 'running' },
        { value: 9, label: 'To do', type: 'warning' },
        { value: 2, label: 'Failed', type: 'error' }
      ],
      selected: null
    };
  },

  methods: {
    // One item moves on: the bar is told nothing but the new counts, and
    // redraws the shares itself.
    advance() {
      const todo = this.segments[2];

      if (todo.value === 0) {
        return;
      }

      todo.value -= 1;
      this.segments[0].value += 1;
    },

    fail() {
      const running = this.segments[1];

      if (running.value === 0) {
        return;
      }

      running.value -= 1;
      this.segments[3].value += 1;
    },

    onSelect(part) {
      this.selected = part;
    }
  }
};
</script>
