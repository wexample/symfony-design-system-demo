<script>
import DateDisplay from '@wexample/symfony-design-system/components/date-display/date-display.vue';

export default {
  template: '#vue-template-wexample-symfony-design-system-demo-bundle-vue-partials-demo-dates',

  components: {
    DateDisplay
  },

  data() {
    return {
      // Fixed at mount, so what moves on screen is the reading of it and not the
      // value itself.
      mountedAt: new Date().toISOString()
    };
  }
};
</script>
