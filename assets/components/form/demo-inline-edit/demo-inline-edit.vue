<script>
import InlineEdit from '@wexample/symfony-design-system/components/form/inline-edit/inline-edit.vue';

// A choice among titles, the one chosen renamed in place: what a bar does
// with its steps — a select, its pencil, the new title back in the list.
export default {
  template: '#vue-template-wexample-symfony-design-system-demo-bundle-components-form-demo-inline-edit-demo-inline-edit',

  components: {
    InlineEdit
  },

  props: {
    form: {
      type: Object,
      required: true
    },
    label: {
      type: String,
      default: null
    }
  },

  data() {
    return {
      items: [
        { id: 'intro', title: 'Introduction' },
        { id: 'quote', title: 'Quote' },
        { id: 'closing', title: 'Closing' }
      ],
      current: 'intro'
    };
  },

  computed: {
    currentTitle() {
      return this.items.find((item) => item.id === this.current)?.title ?? '';
    }
  },

  methods: {
    onSaved({ value }) {
      this.items = this.items.map((item) => (item.id === this.current ? { ...item, title: value } : item));
    }
  }
};
</script>
