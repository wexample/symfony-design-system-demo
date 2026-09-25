<script>
import DataTable from '@wexample/symfony-design-system/components/data-table/data-table.vue';

// Rows that can be ticked, and what the table tells its parent: the ticked keys
// held here through `v-model:selected`, and each action as it is pressed. The
// actions have no address, so nothing is posted — what would have been is shown.
export default {
  template: '#vue-template-wexample-symfony-design-system-demo-bundle-components-collection-table-demo-selectable-table-demo-selectable-table',

  components: {
    DataTable
  },

  props: {
    // `buttons` or `select`: the two shapes of the same bar.
    mode: {
      type: String,
      default: 'buttons'
    }
  },

  data() {
    return {
      selected: [],
      lastAction: null,
      rows: [
        { id: 'alpha', name: 'Alpha', owner: 'Design' },
        { id: 'beta', name: 'Beta', owner: 'Platform' },
        { id: 'gamma', name: 'Gamma', owner: 'Design' },
        { id: 'delta', name: 'Delta', owner: 'Support' }
      ],
      columns: [
        { key: 'name', label: 'Name' },
        { key: 'owner', label: 'Owner', secondary: true }
      ],
      // Narrowed by the table itself: the four rows are all there is.
      filters: [
        {
          key: 'owner',
          label: 'Owner',
          multiple: true,
          options: [
            { value: 'Design', label: 'Design', tone: 'cat-lavender' },
            { value: 'Platform', label: 'Platform', tone: 'cat-aqua' },
            { value: 'Support', label: 'Support', tone: 'cat-sunflower' }
          ]
        }
      ],
      bulkActions: [
        { key: 'archive', label: 'Archive', icon: 'ph:bold/archive' },
        { key: 'export', label: 'Export', icon: 'ph:bold/download-simple' },
        { key: 'delete', label: 'Delete', icon: 'ph:bold/trash' },
        // Needs nothing ticked: it takes every row, on every page.
        { key: 'archive-all', label: 'Archive all', icon: 'ph:bold/archive', all: true, class: 'button--invert' }
      ]
    };
  },

  methods: {
    getRowKey(row) {
      return row.id;
    },

    onBulkAction({ action, keys }) {
      this.lastAction = `${action.label}: ${keys.join(', ')}`;
    }
  }
};
</script>
