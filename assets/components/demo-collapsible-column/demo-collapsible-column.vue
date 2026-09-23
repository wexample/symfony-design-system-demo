<script>
import ZoneCollapsible from '@wexample/symfony-design-system/components/zone-collapsible/zone-collapsible.vue';

// The same column as the four twig ones beside it, rendered by a vue: what it
// shows is that a workspace written entirely in vue folds its regions with the
// same component, the same classes and the same memory.
export default {
  template: '#vue-template-wexample-symfony-design-system-demo-bundle-components-demo-collapsible-column-demo-collapsible-column',

  components: {
    ZoneCollapsible
  },

  props: {
    stateId: {
      type: String,
      default: null
    },
    title: {
      type: String,
      required: true
    },
    items: {
      type: Array,
      default: () => []
    },
    body: {
      type: String,
      default: ''
    },
    collapsed: {
      type: Boolean,
      default: false
    }
  }
};
</script>
