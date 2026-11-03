<script>
import ContextMenu from '@wexample/symfony-design-system/components/context-menu/context-menu.vue';

// Files in a list, each with its own menu on a right click: what is offered
// depends on the file — a pinned one is unpinned, a locked one is not
// deleted —, and what was chosen is said under the list.
export default {
  template: '#vue-template-wexample-symfony-design-system-demo-bundle-components-demo-context-menu-demo-context-menu',

  components: {
    ContextMenu
  },

  props: {
    labels: {
      type: Object,
      required: true
    }
  },

  data() {
    return {
      files: [
        { id: 'brief', name: 'brief.md', icon: 'ph:bold/file-text', pinned: true, locked: false },
        { id: 'budget', name: 'budget.xlsx', icon: 'ph:bold/file-xls', pinned: false, locked: true },
        { id: 'cover', name: 'cover.png', icon: 'ph:bold/file-image', pinned: false, locked: false }
      ],
      current: null,
      chosen: ''
    };
  },

  computed: {
    items() {
      const file = this.files.find((candidate) => candidate.id === this.current);

      if (!file) {
        return [];
      }

      return [
        { separator: true, label: file.name },
        { icon: 'ph:bold/arrow-square-out', label: this.labels.open, value: 'open' },
        { icon: 'ph:bold/pencil-simple', label: this.labels.rename, value: 'rename' },
        { label: this.labels.pinned, value: 'pin', checked: file.pinned },
        { separator: true },
        { icon: 'ph:bold/trash', label: this.labels.delete, value: 'delete', disabled: file.locked }
      ];
    }
  },

  methods: {
    iconHtml(name) {
      return this.app.getServiceOrFail('icon').icon(name);
    },

    // Which file the right click fell on: its menu is the one shown.
    onTarget({ target }) {
      this.current = target?.closest?.('[data-file]')?.dataset.file ?? null;
    },

    onSelect(item) {
      this.chosen = `${item.label} · ${this.files.find((file) => file.id === this.current)?.name ?? ''}`;
    },

    onToggle({ checked }) {
      this.files = this.files.map((file) => (file.id === this.current ? { ...file, pinned: checked } : file));
      this.chosen = `${this.labels.pinned} · ${checked ? '✓' : '✗'}`;
    }
  }
};
</script>
