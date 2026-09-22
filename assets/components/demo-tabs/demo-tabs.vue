<script>

import Tab from '@wexample/symfony-design-system/components/tab/tab.vue';

// What the twig tabs cannot do: the bar is controlled, so something has to hold
// which one is open. Server-side that something is the address, and each item is
// a link; here it is this component, and the bar only says that a tab was asked
// for. Nothing else changes — the same items, the same classes.
export default {
  template: '#vue-template-wexample-symfony-design-system-demo-bundle-components-demo-tabs-demo-tabs',

  components: {
    Tab
  },

  props: {
    items: {
      type: Array,
      required: true
    }
  },

  data() {
    return {
      activeKey: this.items[0]?.key || ''
    };
  },

  computed: {
    activePanel() {
      const active = this.items.find((item) => item.key === this.activeKey);

      return active ? active.panel : '';
    }
  },

  methods: {
    onChange(key) {
      this.activeKey = key;
    }
  }
};
</script>
