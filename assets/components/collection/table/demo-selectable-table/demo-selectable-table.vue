<script>
import DataTable from '@wexample/symfony-design-system/components/data-table/data-table.vue';

// Rows that can be ticked, and what the table tells its parent: the ticked keys
// held here through `v-model:selected`, the order through `v-model:sort`, and
// each action as it is pressed. The actions have no address, so nothing is
// posted — what would have been is shown.
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
      // Sorted by the table itself, the rows being all there is: the
      // order comes back here only to be shown.
      sort: null,
      defaultSort: { key: 'name', direction: 'asc' },
      lastAction: null,
      // A read that failed, stood in for the rows with a way to try again.
      error: '',
      rows: [
        { id: 'alpha', name: 'Alpha', owner: 'Design' },
        { id: 'beta', name: 'Beta', owner: 'Platform' },
        { id: 'gamma', name: 'Gamma', owner: 'Design' },
        { id: 'delta', name: 'Delta', owner: 'Support' },
        { id: 'epsilon', name: 'Épsilon', owner: 'Platform' }
      ],
      columns: [
        { key: 'name', label: 'Name', sortable: true },
        { key: 'owner', label: 'Owner', secondary: true, sortable: true }
      ],
      // Narrowed by the table itself: the rows are all there is.
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
    },

    // What a page would open in a modal: here, only said.
    onRowActivate({ row }) {
      this.lastAction = `Open: ${row.name}`;
    },

    failRead() {
      this.error = 'The list could not be read.';
    },

    retry() {
      this.error = '';
    }
  }
};
</script>
