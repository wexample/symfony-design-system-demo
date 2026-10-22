<script>
import AbstractEntityTable from '@wexample/symfony-design-system/components/collection/table/abstract-entity-table/abstract-entity-table.vue';
import { filterTextMatches } from '@wexample/symfony-design-system/js/Helper/FilterHelper';
import { sortApply, sortFromQuery } from '@wexample/symfony-design-system/js/Helper/SortHelper';

const STATUSES = ['Active', 'Pending', 'Inactive'];
// What each word is drawn as: a status cell takes a type, never a colour.
const STATUS_TYPES = { Active: 'success', Pending: 'pending', Inactive: 'disabled' };
const NOW = Date.now();
const DEMO_ROWS = Array.from({ length: 37 }, (value, index) => ({
  name: `Item ${String(index + 1).padStart(2, '0')}`,
  status: STATUSES[index % STATUSES.length],
  amount: `${(index + 1) * 7.5} €`,
  created: `2026-${String((index % 12) + 1).padStart(2, '0')}-15T09:45:00`,
  // Close enough to now that the cells of the page being read redraw themselves.
  seen: new Date(NOW - index * 37 * 1000).toISOString(),
}));

export default {
  extends: AbstractEntityTable,

  template: "#vue-template-wexample-symfony-design-system-demo-bundle-vue-collection-table-demo-entity-table",

  data() {
    return {
      searchable: true,
      defaultSort: { key: 'created', direction: 'desc' },
    };
  },

  methods: {
    getEntityClass() {
      return null;
    },

    // Stands in for the API: reads the same `search` and `sort` an endpoint
    // would be sent, then slices a fixed dataset and reports the same
    // pagination meta a paginated endpoint would return.
    async refreshEntitiesCollection() {
      const { search, sort } = this.getCollectionQuery();
      const found = sortApply(
        DEMO_ROWS.filter((row) => !search || filterTextMatches(search, row.name, row.status, row.amount)),
        sortFromQuery(sort),
        { value: (row, key) => (key === 'amount' ? parseFloat(row.amount) : row[key]) }
      );
      const length = this.getPageLength();
      const offset = this.page * length;

      this.entities = found.slice(offset, offset + length);
      this.pagination = {
        page: this.page,
        length,
        total: found.length,
        pagesCount: Math.ceil(found.length / length),
        hasMore: offset + length < found.length,
      };
    },

    getColumnsConfiguration() {
      return [
        { key: 'name',    label: 'Name', sortable: true },
        { key: 'status',  label: 'Status', align: 'center', cell: 'status', sortable: true, format: (value) => ({ type: STATUS_TYPES[value], label: value }) },
        { key: 'amount',  label: 'Amount', align: 'right', sortable: true },
        { key: 'created', label: 'Created', secondary: true, sortable: true, format: (v) => this.cellFormatterDateOnly(v) },
        // Live on every page: the cells are rebuilt as the pager moves, and each
        // one keeps counting on its own afterwards.
        { key: 'seen', label: 'Last seen', secondary: true, cell: 'date' },
        {
          label: false,
          align: 'center',
          target: 'modal',
          targetOptions: { closeOnEscape: true, closeOnOverlayClick: true },
          actions: [
            { name: 'show', route: 'wexample_design_system_generic_overlays_modal_test_simple' },
            {
              name: 'edit',
              route: 'wexample_design_system_generic_overlays_modal_test_medium',
              target: 'panel',
            },
          ],
        },
      ];
    },
  },
};
</script>
