<script>
import ButtonMenu from '@wexample/symfony-design-system/components/button-menu/button-menu.vue';
import Tree from '@wexample/symfony-design-system/components/tree/tree.vue';

// The panel an IDE puts its files in: a header that never scrolls, holding what
// is being looked at and what can be done to it, over a tree that scrolls on
// both axes. Everything here is the design system's; what the demo adds is the
// arrangement.
export default {
  template: '#vue-template-wexample-symfony-design-system-demo-bundle-components-partials-demo-explorer-demo-explorer',

  components: {
    ButtonMenu,
    Tree
  },

  props: {
    items: {
      type: Array,
      default: () => []
    }
  },

  data() {
    return {
      // The same item shape the twig menu takes, which is the whole point of the
      // two being twins: this list would render identically on either side.
      scopes: [
        { icon: 'ph:bold/folder-open', label: 'Project' },
        { icon: 'ph:bold/package', label: 'Packages' },
        { icon: 'ph:bold/note', label: 'Scratches' },
        { icon: 'ph:bold/git-branch', label: 'Changed Files' }
      ],
      scope: 'Project',
      lastAction: null
    };
  },

  methods: {
    onScopeSelect(item) {
      this.scope = item.label;
    },

    onAction(name) {
      this.lastAction = name;
    }
  }
};
</script>
