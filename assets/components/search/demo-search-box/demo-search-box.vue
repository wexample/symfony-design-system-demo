<script>
import SearchBox from '@wexample/symfony-search/vue/search/search-box.vue';
import DemoMessage from '../result/demo-message/demo-message.vue';
import DemoRoom from '../result/demo-room/demo-room.vue';

// The showcase's search: the design system's box, plus one row component per
// entity the demo bundle makes findable. Registering them under the type's
// name is the whole customisation — the box resolves `search-result-<type>`
// and falls back to its default row for whatever has none, pages included.
export default {
  extends: SearchBox,

  template: '#vue-template-wexample-symfony-design-system-demo-bundle-components-search-demo-search-box-demo-search-box',

  components: {
    DemoMessage,
    DemoRoom
  }
};
</script>
