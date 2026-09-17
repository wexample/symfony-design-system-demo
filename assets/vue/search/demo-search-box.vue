<script>
import SearchBox from '@wexample/symfony-search/vue/search/search-box.vue';
import SearchResultDemoMessage from './result/demo-message.vue';
import SearchResultDemoRoom from './result/demo-room.vue';

// The showcase's search: the design system's box, plus one row component per
// entity the demo bundle makes findable. Registering them under the type's
// name is the whole customisation — the box resolves `search-result-<type>`
// and falls back to its default row for whatever has none, pages included.
export default {
  extends: SearchBox,

  template: '#vue-template-wexample-symfony-design-system-demo-bundle-vue-search-demo-search-box',

  components: {
    SearchResultDemoMessage,
    SearchResultDemoRoom
  }
};
</script>
