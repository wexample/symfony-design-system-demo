<script>
import FileExplorer from '@wexample/symfony-design-system/components/file-explorer/file-explorer.vue';

// A small file system held in memory, answered the way an api answers a
// folder: a page of its content and how much it holds in all, after a short
// wait so the loading shows. The explorer knows no more than that.
const FILES = {
  '': ['docs/', 'src/', 'assets/', 'README.md', 'composer.json', 'package.json', '.env'],
  docs: ['guide/', 'architecture.md', 'changelog.md', 'logo.svg', 'screenshot.png', 'budget.xlsx'],
  'docs/guide': ['install.md', 'usage.md', 'faq.md', 'video-intro.mp4'],
  src: ['Controller/', 'Entity/', 'Kernel.php', 'bootstrap.sql'],
  'src/Controller': ['HomeController.php', 'FileController.php'],
  'src/Entity': ['File.php', 'Folder.php'],
  assets: ['app.ts', 'app.scss', 'layout.vue', 'archive.zip', 'report.pdf', 'data.csv', 'notes.txt',
    ...Array.from({ length: 40 }, (value, index) => `photo-${String(index + 1).padStart(2, '0')}.jpg`)]
};

const PAGE_LENGTH = 30;

const entry = (folder, name, index) => {
  const directory = name.endsWith('/');
  const clean = directory ? name.slice(0, -1) : name;
  const path = folder ? `${folder}/${clean}` : clean;

  return {
    id: path,
    name: clean,
    path,
    type: directory ? 'directory' : 'file',
    hasChildren: directory && Boolean(FILES[path]?.length),
    size: directory ? 0 : 900 + ((index * 7919) % 5000000),
    modifiedAt: new Date(Date.UTC(2026, 8, 25 - (index % 20), 9 + (index % 8), index % 60)).toISOString()
  };
};

export default {
  template: '#vue-template-wexample-symfony-design-system-demo-bundle-components-demo-file-explorer-demo-file-explorer',

  components: {
    FileExplorer
  },

  data() {
    return {
      opened: null,
      selected: 0
    };
  },

  methods: {
    loadChildren(folder, page) {
      const path = folder?.path ?? '';
      const names = FILES[path] ?? [];
      const items = names
        .slice(page * PAGE_LENGTH, (page + 1) * PAGE_LENGTH)
        .map((name, index) => entry(path, name, page * PAGE_LENGTH + index));

      return new Promise((resolve) => setTimeout(() => resolve({ items, total: names.length }), 250));
    },

    onOpen(item) {
      this.opened = item.path;
    },

    onSelect(items) {
      this.selected = items.length;
    }
  }
};
</script>
