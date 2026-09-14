<script>
import AbstractEntityTable from '@wexample/symfony-design-system/vue/collection/table/abstract-entity-table.vue';

export default {
  extends: AbstractEntityTable,

  template: "#vue-template-wexample-symfony-design-system-demo-bundle-vue-collection-table-demo-dates-table",

  methods: {
    getEntityClass() {
      return null;
    },

    async refreshEntitiesCollection() {
      const now = Date.now();

      // The fixed dates show the named formats; the recent ones are there to be
      // watched, since that is the only way to see a cell redraw itself.
      this.entities = [
        { label: 'Alpha', date: '2026-01-10T08:30:00', seen: new Date(now - 40 * 1000).toISOString() },
        { label: 'Beta',  date: '2026-02-18T14:15:00', seen: new Date(now - 22 * 60 * 1000).toISOString() },
        { label: 'Gamma', date: '2026-03-25T09:45:00', seen: new Date(now - 3 * 3600 * 1000).toISOString() },
      ];
    },

    getColumnsConfiguration() {
      return [
        { key: 'label',  label: 'Label' },
        { key: 'date',   label: 'Date only',      secondary: true, format: (v) => this.cellFormatterDateOnly(v) },
        { key: 'date',   label: 'Date time',      secondary: true, format: (v) => this.cellFormatterDateTime(v) },
        { key: 'date',   label: 'Date time full', secondary: true, format: (v) => this.cellFormatterDateTimeFull(v) },
        { key: 'date',   label: 'Month year',     secondary: true, format: (v) => this.cellFormatterMonthYear(v) },
        { key: 'date',   label: 'Relative',       secondary: true, format: (v) => this.cellFormatterRelative(v) },
        // Not a formatter but a cell type: what it holds keeps itself true.
        { key: 'seen',   label: 'Live',           secondary: true, cell: 'date' },
      ];
    },
  },
};
</script>
