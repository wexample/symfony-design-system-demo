<script>
import AbstractEntityBarList from '@wexample/symfony-design-system/components/collection/bar-list/abstract-entity-bar-list/abstract-entity-bar-list.vue';
import EventsService from '@wexample/symfony-loader/js/Services/EventsService';

// The event this list listens to. Anything on the page may ring it, and the
// list knows nothing of who did.
export const DEMO_BAR_LIST_REFRESH_EVENT = 'demo-bar-list:refresh';

const ICONS = ['ph:bold/user', 'ph:bold/file-pdf', 'ph:bold/squares-four', 'ph:bold/robot'];
const ROLES = ['Owner', 'Document · 2.4 MB', 'Application · Installed', 'Service account'];
const NOW = Date.now();
const DEMO_ROWS = Array.from({ length: 23 }, (value, index) => ({
  id: index + 1,
  name: `Row ${String(index + 1).padStart(2, '0')}`,
  role: ROLES[index % ROLES.length],
  icon: ICONS[index % ICONS.length],
  // Close enough to now that the dates of the page being read redraw themselves.
  seen: new Date(NOW - index * 47 * 1000).toISOString(),
}));

// Stands in for a collection that grows on its own, so a reading taken later
// differs from one taken now. Without it, no way to see a refresh happen.
const STARTED_AT = Date.now();

export default {
  extends: AbstractEntityBarList,

  template: '#vue-template-wexample-symfony-design-system-demo-bundle-components-collection-bar-list-demo-bar-list-demo-bar-list',

  props: {
    // Which of the three ways in this list is given. None of them is the list's
    // nature: the same list takes whichever its situation offers.
    watch: {
      type: String,
      default: 'none'
    },
    showToolbar: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      pageLength: 5,
      readCount: 0
    };
  },

  methods: {
    getEntityClass() {
      return null;
    },

    getCollectionRefreshEvents() {
      return this.watch === 'events' ? [DEMO_BAR_LIST_REFRESH_EVENT] : [];
    },

    getPollingIntervalMs() {
      return this.watch === 'polling' ? 3000 : null;
    },

    // The demo has no server to publish for it, so the live case is played by
    // hand: the button below rings the same message a topic would have carried.
    getLiveSource() {
      return null;
    },

    // Stands in for the API: slices a dataset that grows with time, and reports
    // the same pagination meta a paginated endpoint would return.
    async refreshEntitiesCollection() {
      this.readCount += 1;

      const age = Math.floor((Date.now() - STARTED_AT) / 1000);
      const available = DEMO_ROWS.slice(0, Math.min(DEMO_ROWS.length, 8 + Math.floor(age / 5)));
      const length = this.getPageLength();
      const offset = this.page * length;

      this.entities = available.slice(offset, offset + length);
      this.pagination = {
        page: this.page,
        length,
        total: available.length,
        pagesCount: Math.ceil(available.length / length),
        hasMore: offset + length < available.length,
      };
    },

    getBarConfiguration(entity) {
      return {
        title: entity.name,
        subtitle: entity.role,
        icon: entity.icon,
        href: '#',
        date: entity.seen
      };
    },

    // What a live message would have done, had there been a server to send one.
    simulateLiveMessage() {
      this.onLiveSourceMessage({ event: 'demo' }, {});
    },

    ringRefreshEvent() {
      this.app.getServiceOrFail(EventsService).trigger(DEMO_BAR_LIST_REFRESH_EVENT);
    }
  }
};
</script>
