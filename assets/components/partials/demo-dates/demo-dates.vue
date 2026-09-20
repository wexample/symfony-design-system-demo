<script>
import DateDisplay from '@wexample/symfony-design-system/components/date/date.vue';

export default {
  template: '#vue-template-wexample-symfony-design-system-demo-bundle-components-partials-demo-dates-demo-dates',

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
