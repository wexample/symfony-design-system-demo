<script>
import SearchResult from '@wexample/symfony-design-system/vue/search/search-result.vue';

// A room found. It declares no route yet, so the bar stands without a link:
// what a click on a room should open is still to be decided.
export default {
  extends: SearchResult,

  template: '#vue-template-wexample-symfony-design-system-demo-bundle-vue-search-result-demo-room',

  computed: {
    icon() {
      return 'ph:bold/chats';
    },

    typeLabel() {
      return 'Room';
    }
  }
};
</script>
