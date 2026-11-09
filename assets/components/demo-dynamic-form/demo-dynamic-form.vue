<script>
import DynamicForm from '@wexample/symfony-design-system/components/form/dynamic-form/dynamic-form.vue';
import { dynamicFormCodeField } from '@wexample/symfony-coding/js/Helper/DynamicFormHelper';

// A form from the server drawn as an application draws it: handed the code
// editor for the fields of code, which the design system does not draw
// itself — a collection's items among them.
export default {
  template: '#vue-template-wexample-symfony-design-system-demo-bundle-components-demo-dynamic-form-demo-dynamic-form',

  components: {
    DynamicForm
  },

  props: {
    form: {
      type: Object,
      required: true
    }
  },

  computed: {
    fieldComponents() {
      return { code: dynamicFormCodeField };
    }
  }
};
</script>
