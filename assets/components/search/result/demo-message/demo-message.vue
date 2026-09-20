<script>
import SearchResult from '@wexample/symfony-search/vue/search/search-result.vue';

// A message found: it is said by someone, in a room, so the row says that
// rather than repeating the word "demo_message" at its far end.
export default {
  extends: SearchResult,

  template: '#vue-template-wexample-symfony-design-system-demo-bundle-components-search-result-demo-message-demo-message',

  computed: {
    icon() {
      return 'ph:bold/chat-circle-text';
    },

    typeLabel() {
      return 'Message';
    }
  }
};
</script>
