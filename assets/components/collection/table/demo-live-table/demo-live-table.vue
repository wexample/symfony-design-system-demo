<script>
import AbstractEntityTable from '@wexample/symfony-design-system/components/collection/table/abstract-entity-table/abstract-entity-table.vue';
import EventsService from '@wexample/symfony-loader/js/Services/EventsService';

// The event this table listens to. Anything on the page may ring it — a button,
// a form coming back, another component — and the table knows nothing of who did.
export const DEMO_TABLE_REFRESH_EVENT = 'demo-table:refresh';

// Stands in for an endpoint: a collection that grows on its own, so a reading
// taken later differs from one taken now. Without that, no way to see whether a
// refresh happened at all.
const STARTED_AT = Date.now();

export default {
  extends: AbstractEntityTable,

  template: '#vue-template-wexample-symfony-design-system-demo-bundle-components-collection-table-demo-live-table-demo-live-table',

  props: {
    // Which of the three ways in this table is given. None of them is the
    // table's nature: the same table takes whichever its situation offers.
    watch: {
      type: String,
      default: 'none'
    }
  },

  data() {
    return {
      readCount: 0,
      lastReadAt: null
    };
  },

  methods: {
    getEntityClass() {
      return null;
    },

    getCollectionRefreshEvents() {
      return this.watch === 'events' ? [DEMO_TABLE_REFRESH_EVENT] : [];
    },

    getPollingIntervalMs() {
      return this.watch === 'polling' ? 3000 : null;
    },

    // The demo has no server to publish for it, so the live case is played by
    // hand: the button below rings the same message a topic would have carried.
    getLiveSource() {
      return null;
    },

    async refreshEntitiesCollection() {
      this.readCount += 1;
      this.lastReadAt = new Date().toISOString();

      const age = Math.floor((Date.now() - STARTED_AT) / 1000);

      this.entities = Array.from({ length: Math.min(3 + Math.floor(age / 5), 8) }, (value, index) => ({
        name: `Run ${String(index + 1).padStart(2, '0')}`,
        state: index === 0 ? 'running' : 'complete',
        read: this.readCount
      }));
    },

    getColumnsConfiguration() {
      return [
        { key: 'name', label: 'Run' },
        { key: 'state', label: 'State' },
        { key: 'read', label: 'Read #', align: 'right', secondary: true }
      ];
    },

    // What a live message would have done, had there been a server to send one.
    simulateLiveMessage() {
      this.onLiveSourceMessage({ event: 'demo' }, {});
    },

    ringRefreshEvent() {
      this.app.getServiceOrFail(EventsService).trigger(DEMO_TABLE_REFRESH_EVENT);
    }
  }
};
</script>
