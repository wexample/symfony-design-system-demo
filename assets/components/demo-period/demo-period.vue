<script>
import PeriodNav from '@wexample/symfony-design-system/components/period-nav/period-nav.vue';

// The period navigation and what it tells: the period, written as the
// address keeps it, and the bounds a list or a chart would ask for.
export default {
  template: '#vue-template-wexample-symfony-design-system-demo-bundle-components-demo-period-demo-period',

  components: {
    PeriodNav
  },

  data() {
    return {
      // Two years back at most, to show a bound.
      min: String(new Date().getFullYear() - 2),
      period: null
    };
  }
};
</script>
