<script>
import TagsInput from '@wexample/symfony-design-system/components/form/tags-input/tags-input.vue';
import RoutingService from '@wexample/symfony-loader/js/Services/RoutingService';

// The vue face of DemoStampsInputType: the design system's tags field, its
// defaults those of the type — the straw tone, a tag before each word, the
// stamps known so far asked as the words are typed. Nothing drawn of its
// own: the template, the behaviour and the style are the tags field's.
export default {
  extends: TagsInput,

  // Its own id, the tags field's markup under it (demo-stamps-input.vue.twig).
  template: '#vue-template-wexample-symfony-design-system-demo-bundle-components-form-demo-stamps-input-demo-stamps-input',

  props: {
    tone: {
      type: String,
      default: 'cat-straw'
    },
    glyph: {
      type: String,
      default: 'ph:bold/tag'
    }
  },

  methods: {
    getSuggestionsUrl() {
      return this.suggestionsUrl ?? this.app.getServiceOrFail(RoutingService).generate('wexample_design_system_generic_form_stamps');
    }
  }
};
</script>
