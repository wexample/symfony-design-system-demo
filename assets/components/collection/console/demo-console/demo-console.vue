<script>
import AbstractEntityConsole from '@wexample/symfony-design-system/components/collection/console/abstract-entity-console/abstract-entity-console.vue';

// What a run would write of itself, one kind after the other.
const KINDS = [
  { name: 'request', tone: 'info', text: 'Llm request sent to the model, 3 messages in context' },
  { name: 'saved', tone: null, text: 'Message saved, the answer kept with the session' },
  { name: 'done', tone: 'success', text: 'Step completed, the next one opens' },
  { name: 'warning', tone: 'warning', text: 'Tool answered late, 4.2 s over the budget it was given' },
  { name: 'saved', tone: null, text: 'Message saved, what the visitor typed' },
  { name: 'error', tone: 'error', text: 'Model refused: the prompt holds a key the session may not read' }
];
const REFS = ['ask-name', 'greet', 'lookup', 'answer'];
// How often a line is written, and how many stand there when the page opens.
const LINE_INTERVAL_MS = 3000;
const LINES_AT_START = 12;
const LINES_AT_MOST = 200;
const STARTED_AT = Date.now();

// A line of the run, as the api would answer it — with the one method the
// collection calls on what it already holds.
class DemoLine {
  constructor(index) {
    const kind = KINDS[index % KINDS.length];

    this.id = `line-${index + 1}`;
    this.name = kind.name;
    this.tone = kind.tone;
    this.ref = REFS[index % REFS.length];
    this.text = kind.text;
    this.dateCreated = new Date(STARTED_AT + (index - LINES_AT_START) * LINE_INTERVAL_MS).toISOString();
  }

  absorb() {}
}

// The console read from a run that goes on while the page is open: a line
// every few seconds, read by polling. Latest first by default, oldest first
// when asked (`order`), the same lines either way.
export default {
  extends: AbstractEntityConsole,

  template: '#vue-template-wexample-symfony-design-system-demo-bundle-components-collection-console-demo-console-demo-console',

  props: {
    // `newest` or `oldest`: which end the console is read from.
    order: {
      type: String,
      default: 'newest'
    },
    showToolbar: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      pageLength: 20,
      // What was last pointed at: a ref clicked, a line clicked.
      picked: null
    };
  },

  methods: {
    getEntityClass() {
      return null;
    },

    isNewestFirst() {
      return this.order !== 'oldest';
    },

    getPollingIntervalMs() {
      return LINE_INTERVAL_MS;
    },

    // Stands in for the api: the lines written so far, paged the way the
    // console asks — the freshest page first, or the pages in order with
    // the last one reachable as `-1`.
    async fetchEntitiesPage(page) {
      const age = Math.floor((Date.now() - STARTED_AT) / LINE_INTERVAL_MS);
      const total = Math.min(LINES_AT_MOST, LINES_AT_START + age);
      const length = this.getPageLength();
      const pagesCount = Math.max(1, Math.ceil(total / length));
      const resolved = page < 0 ? pagesCount + page : page;
      const all = Array.from({ length: total }, (value, index) => new DemoLine(index));
      const ordered = this.isNewestFirst() ? all.reverse() : all;
      const offset = resolved * length;

      return {
        items: ordered.slice(offset, offset + length),
        pagination: {
          page: resolved,
          length,
          total,
          pagesCount,
          hasMore: offset + length < total
        }
      };
    },

    getLineConfiguration(line) {
      return {
        time: line.dateCreated,
        tone: line.tone,
        kind: line.name,
        ref: { label: line.ref, onClick: (entity) => { this.picked = `slot ${entity.ref}`; } },
        text: line.text,
        end: line.id,
        onClick: (entity) => { this.picked = entity.id; }
      };
    }
  }
};
</script>
